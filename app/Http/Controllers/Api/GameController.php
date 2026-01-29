<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Character;
use App\Models\CharacterScenario;
use App\Models\Conversation;
use App\Models\Game;
use App\Models\Message;
use App\Models\Room;
use App\Models\Rule;
use App\Models\RuleAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class GameController extends Controller
{
    /**
     * Start a new game.
     * 
     * Algorithm:
     * 1. Create game record with user_id, ai_model_id (default 1 for now), impostor_character_id (set later)
     * 2. Pick 2-4 random characters from the database
     * 3. Randomly choose one to be the impostor
     * 4. For every room, pick 3 random rules and store them in game_rule table
     * 5. For each character, create 3 steps (step_order 1, 2, 3):
     *    - Pick a random room
     *    - From the rules generated for that room, choose an action
     *    - If not impostor: choose a valid action (is_violation = false)
     *    - If impostor: choose an invalid action (is_violation = true) but only ONE invalid action total
     * 6. Return all game data as JSON
     */
    public function start(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            // Get the authenticated user
            $user = auth()->user();

            // For now, use AI model ID 1 (gpt-oss-20b), later this will come from frontend
            $aiModelId = $request->input('ai_model_id', 1);

            // Step 1: Pick 2-4 random characters
            $characterCount = rand(2, 4);
            $characters = Character::inRandomOrder()->limit($characterCount)->get();

            if ($characters->count() < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not enough characters in the database. Need at least 2 characters. CHANGE LATER',
                ], 500);
            }

            // Step 2: Randomly choose one character to be the impostor
            $impostorCharacter = $characters->random();

            // Step 3: Create the game record
            $game = Game::create([
                'user_id' => $user->id,
                'ai_model_id' => $aiModelId,
                'impostor_character_id' => $impostorCharacter->id,
            ]);

            // Step 4: For every room, pick 3 random rules and store them in game_rule table
            $rooms = Room::with('rules')->get();
            $gameRulesPerRoom = []; // Track which rules are assigned to each room for this game

            foreach ($rooms as $room) {
                // Get 3 random rules for this room (or all if less than 3)
                $roomRules = $room->rules()->inRandomOrder()->limit(3)->get();
                
                foreach ($roomRules as $rule) {
                    // Attach rule to game via pivot table
                    $game->rules()->attach($rule->id);
                }

                // Store the selected rules for this room for later use
                $gameRulesPerRoom[$room->id] = $roomRules;
            }

            // Step 5: Create character scenarios (3 steps per character)
            $characterScenarios = [];
            $impostorHasViolated = false; // Track if impostor has already done their one violation

            foreach ($characters as $character) {
                $isImpostor = $character->id === $impostorCharacter->id;

                for ($stepOrder = 1; $stepOrder <= 3; $stepOrder++) {
                    // Pick a random room that has rules assigned
                    $roomsWithRules = array_keys(array_filter($gameRulesPerRoom, fn($rules) => $rules->count() > 0));
                    
                    if (empty($roomsWithRules)) {
                        throw new \Exception('No rooms with rules available.');
                    }

                    $randomRoomId = $roomsWithRules[array_rand($roomsWithRules)];
                    $selectedRoom = $rooms->find($randomRoomId);
                    $availableRules = $gameRulesPerRoom[$randomRoomId];

                    // Pick a random rule from the available rules for this room
                    $selectedRule = $availableRules->random();

                    // Determine which action to pick based on impostor status
                    if ($isImpostor && !$impostorHasViolated) {
                        // Impostor gets ONE violation action
                        $action = RuleAction::where('rule_id', $selectedRule->id)
                            ->where('is_violation', true)
                            ->inRandomOrder()
                            ->first();
                        
                        if ($action) {
                            $impostorHasViolated = true;
                        } else {
                            // If no violation action exists, pick a valid one
                            $action = RuleAction::where('rule_id', $selectedRule->id)
                                ->where('is_violation', false)
                                ->inRandomOrder()
                                ->first();
                        }
                    } else {
                        // Non-impostor or impostor who already violated: pick valid action
                        $action = RuleAction::where('rule_id', $selectedRule->id)
                            ->where('is_violation', false)
                            ->inRandomOrder()
                            ->first();
                    }

                    if (!$action) {
                        // Fallback: get any action for this rule
                        $action = RuleAction::where('rule_id', $selectedRule->id)
                            ->inRandomOrder()
                            ->first();
                    }

                    if (!$action) {
                        throw new \Exception("No actions found for rule ID: {$selectedRule->id}");
                    }

                    // Create the character scenario
                    $scenario = CharacterScenario::create([
                        'game_id' => $game->id,
                        'character_id' => $character->id,
                        'room_id' => $selectedRoom->id,
                        'rule_id' => $selectedRule->id,
                        'action_id' => $action->id,
                        'step_order' => $stepOrder,
                    ]);

                    $characterScenarios[] = $scenario;
                }
            }

            DB::commit();

            // Step 6: Load all relationships and return the complete game data
            $game->load([
                'user',
                'aiModel',
                'impostorCharacter',
                'rules.room',
                'rules.actions',
                'characterScenarios.character',
                'characterScenarios.room',
                'characterScenarios.rule',
                'characterScenarios.action',
            ]);

            // Format the response
            $response = [
                'success' => true,
                'message' => 'Game started successfully',
                'game' => [
                    'id' => $game->id,
                    'created_at' => $game->created_at,
                    'user' => [
                        'id' => $game->user->id,
                        'name' => $game->user->name,
                    ],
                    'ai_model' => [
                        'id' => $game->aiModel->id,
                        'name' => $game->aiModel->name,
                        'provider' => $game->aiModel->provider,
                    ],
                    'characters' => $characters->map(function ($character) use ($impostorCharacter) {
                        return [
                            'id' => $character->id,
                            'name' => $character->name,
                            'personality_description' => $character->personality_description,
                            'is_impostor' => $character->id === $impostorCharacter->id,
                        ];
                    }),
                    'rooms_with_rules' => $rooms->map(function ($room) use ($gameRulesPerRoom) {
                        return [
                            'id' => $room->id,
                            'name' => $room->name,
                            'description' => $room->description,
                            'selected_rules' => isset($gameRulesPerRoom[$room->id]) 
                                ? $gameRulesPerRoom[$room->id]->map(function ($rule) {
                                    return [
                                        'id' => $rule->id,
                                        'rule_text' => $rule->rule_text,
                                    ];
                                }) 
                                : [],
                        ];
                    }),
                    'character_scenarios' => $characters->map(function ($character) use ($game, $impostorCharacter) {
                        $scenarios = $game->characterScenarios
                            ->where('character_id', $character->id)
                            ->sortBy('step_order')
                            ->values();
                        
                        return [
                            'character' => [
                                'id' => $character->id,
                                'name' => $character->name,
                                'is_impostor' => $character->id === $impostorCharacter->id,
                            ],
                            'steps' => $scenarios->map(function ($scenario) {
                                return [
                                    'step_order' => $scenario->step_order,
                                    'room' => [
                                        'id' => $scenario->room->id,
                                        'name' => $scenario->room->name,
                                    ],
                                    'rule' => [
                                        'id' => $scenario->rule->id,
                                        'rule_text' => $scenario->rule->rule_text,
                                    ],
                                    'action' => [
                                        'id' => $scenario->action->id,
                                        'action_text' => $scenario->action->action_text,
                                        'is_violation' => $scenario->action->is_violation,
                                    ],
                                ];
                            }),
                        ];
                    }),
                ],
            ];

            return response()->json($response, 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to start game',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all games for the authenticated user.
     * Returns simple info for sidebar/aside display.
     */
    public function index(): JsonResponse
    {
        $user = auth()->user();

        $games = Game::where('user_id', $user->id)
            ->with(['impostorCharacter', 'aiModel'])
            ->orderBy('created_at', 'desc')
            ->get();

        $response = [
            'success' => true,
            'games' => $games->map(function ($game) {
                $isFinished = $game->finished_at !== null;
                
                $gameData = [
                    'id' => $game->id,
                    'created_at' => $game->created_at,
                    'finished_at' => $game->finished_at,
                    'is_finished' => $isFinished,
                    'ai_model' => [
                        'id' => $game->aiModel->id,
                        'name' => $game->aiModel->name,
                    ],
                ];

                // Only include impostor info if the game is finished
                if ($isFinished) {
                    $gameData['impostor'] = [
                        'id' => $game->impostorCharacter->id,
                        'name' => $game->impostorCharacter->name,
                    ];
                }

                return $gameData;
            }),
        ];

        return response()->json($response);
    }

    /**
     * Get detailed information about a specific game.
     * 
     * Returns:
     * - Characters with their personalities and chat history
     * - Rules for every room in the game
     */
    public function show(int $gameId): JsonResponse
    {
        $user = auth()->user();

        // Find the game and verify ownership
        $game = Game::where('id', $gameId)
            ->where('user_id', $user->id)
            ->first();

        if (!$game) {
            return response()->json([
                'success' => false,
                'message' => 'Game not found or you do not have access to it.',
            ], 404);
        }

        // Load all necessary relationships
        $game->load([
            'aiModel',
            'impostorCharacter',
            'characterScenarios.character',
            'characterScenarios.room',
            'characterScenarios.rule',
            'characterScenarios.action',
            'conversations.character',
            'conversations.messages',
            'rules.room',
            
        ]);

        // Get unique characters from character scenarios
        $characters = $game->characterScenarios
            ->pluck('character')
            ->unique('id')
            ->values();

        // Build characters with personalities and chat history
        $charactersData = $characters->map(function ($character) use ($game) {
            // Get the conversation for this character in this game
            $conversation = $game->conversations
                ->where('character_id', $character->id)
                ->first();

            $messages = [];
            if ($conversation) {
                $messages = $conversation->messages
                    ->sortBy('created_at')
                    ->values()
                    ->map(function ($message) {
                        return [
                            'id' => $message->id,
                            'sender' => $message->sender,
                            'message_text' => $message->message_text,
                            'created_at' => $message->created_at,
                        ];
                    });
            }

            return [
                'id' => $character->id,
                'name' => $character->name,
                'personality_description' => $character->personality_description,
                'conversation' => [
                    'id' => $conversation?->id,
                    'messages' => $messages,
                ],
            ];
        });

        // Group rules by room
        $roomsWithRules = $game->rules
            ->groupBy('room_id')
            ->map(function ($rules, $roomId) {
                $room = $rules->first()->room;
                return [
                    'id' => $room->id,
                    'name' => $room->name,
                    'description' => $room->description,
                    'rules' => $rules->map(function ($rule) {
                        return [
                            'id' => $rule->id,
                            'rule_text' => $rule->rule_text,
                        ];
                    })->values(),
                ];
            })
            ->values();

        $response = [
            'success' => true,
            'game' => [
                'id' => $game->id,
                'created_at' => $game->created_at,
                'finished_at' => $game->finished_at,
                'is_finished' => $game->finished_at !== null,
                'ai_model' => [
                    'id' => $game->aiModel->id,
                    'name' => $game->aiModel->name,
                    'provider' => $game->aiModel->provider,
                ],
                'characters' => $charactersData,
                'rooms_with_rules' => $roomsWithRules,
                'impostor' => $game->impostorCharacter->name
                
            ],
        ];

        return response()->json($response);
    }

    /**
     * Send a message to a character in a game and get AI response.
     * 
     * Limits: 3 messages per character per game.
     * Uses LMStudio API (OpenAI-compatible) with gpt-oss model.
     */
    public function sendMessage(Request $request, int $gameId, int $characterId): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $user = auth()->user();
        $maxMessagesPerCharacter = 5;

        // Find the game and verify ownership
        $game = Game::where('id', $gameId)
            ->where('user_id', $user->id)
            ->first();

        if (!$game) {
            return response()->json([
                'success' => false,
                'message' => 'Game not found or you do not have access to it.',
            ], 404);
        }

        // Check if game is already finished
        if ($game->finished_at !== null) {
            return response()->json([
                'success' => false,
                'message' => 'This game has already ended.',
            ], 400);
        }

        // Verify the character is part of this game
        $characterInGame = CharacterScenario::where('game_id', $gameId)
            ->where('character_id', $characterId)
            ->exists();

        if (!$characterInGame) {
            return response()->json([
                'success' => false,
                'message' => 'Character is not part of this game.',
            ], 404);
        }

        // Get or create conversation
        $conversation = Conversation::firstOrCreate([
            'game_id' => $gameId,
            'character_id' => $characterId,
        ]);

        // Check message limit (5 user messages per character)
        $userMessageCount = Message::where('conversation_id', $conversation->id)
            ->where('sender', 'user')
            ->count();

        if ($userMessageCount >= $maxMessagesPerCharacter) {
            return response()->json([
                'success' => false,
                'message' => 'You have reached the maximum of ' . $maxMessagesPerCharacter . ' messages with this character.',
            ], 400);
        }

        // Save user's message
        $userMessage = Message::create([
            'conversation_id' => $conversation->id,
            'sender' => 'user',
            'message_text' => $request->input('message'),
        ]);
        $userMessage->refresh(); // Get the database-generated created_at

        // Get character info and scenarios for system prompt
        $character = Character::find($characterId);
        $game->load(['impostorCharacter', 'rules.room']);
        
        $characterScenarios = CharacterScenario::where('game_id', $gameId)
            ->where('character_id', $characterId)
            ->with(['room', 'rule', 'action'])
            ->orderBy('step_order')
            ->get();

        $isImpostor = $game->impostor_character_id === $characterId;

        // Build system prompt
        $systemPrompt = $this->buildSystemPrompt($character, $characterScenarios, $isImpostor, $game);

        // Get conversation history for context
        $conversationHistory = Message::where('conversation_id', $conversation->id)
            ->orderBy('created_at')
            ->get();

        // Build messages array for LMStudio API
        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
        ];

        foreach ($conversationHistory as $msg) {
            $messages[] = [
                'role' => $msg->sender === 'user' ? 'user' : 'assistant',
                'content' => $msg->message_text,
            ];
        }

        try {
            // Call LMStudio API (OpenAI-compatible endpoint)
            $response = Http::timeout(60)->post('http://localhost:1234/v1/chat/completions', [
                'model' => 'openai/gpt-oss-20b',
                'messages' => $messages,
                'temperature' => 0.8,
                'max_tokens' => 5000,
            ]);

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to get AI response.',
                    'error' => $response->body(),
                ], 500);
            }

            $aiResponseText = $response->json('choices.0.message.content');

            // Save AI's response
            $aiMessage = Message::create([
                'conversation_id' => $conversation->id,
                'sender' => 'character',
                'message_text' => $aiResponseText,
            ]);
            $aiMessage->refresh(); // Get the database-generated created_at

            return response()->json([
                'success' => true,
                'user_message' => [
                    'id' => $userMessage->id,
                    'sender' => $userMessage->sender,
                    'message_text' => $userMessage->message_text,
                    'created_at' => $userMessage->created_at,
                ],
                'ai_response' => [
                    'id' => $aiMessage->id,
                    'sender' => $aiMessage->sender,
                    'message_text' => $aiMessage->message_text,
                    'created_at' => $aiMessage->created_at,
                ],
                'messages_remaining' => $maxMessagesPerCharacter - ($userMessageCount + 1),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to communicate with AI service.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Submit a guess for who the impostor is.
     * 
     * Marks the game as finished and returns whether the guess was correct.
     */
    public function guess(Request $request, int $gameId): JsonResponse
    {
        $request->validate([
            'character_id' => 'required|integer|exists:characters,id',
        ]);

        $user = auth()->user();

        // Find the game and verify ownership
        $game = Game::where('id', $gameId)
            ->where('user_id', $user->id)
            ->first();

        if (!$game) {
            return response()->json([
                'success' => false,
                'message' => 'Game not found or you do not have access to it.',
            ], 404);
        }

        // Check if game is already finished
        if ($game->finished_at !== null) {
            return response()->json([
                'success' => false,
                'message' => 'This game has already ended.',
            ], 400);
        }

        // Verify the character is part of this game
        $characterInGame = CharacterScenario::where('game_id', $gameId)
            ->where('character_id', $request->input('character_id'))
            ->exists();

        if (!$characterInGame) {
            return response()->json([
                'success' => false,
                'message' => 'Character is not part of this game.',
            ], 404);
        }

        $guessedCharacterId = $request->input('character_id');
        $isCorrect = $game->impostor_character_id === $guessedCharacterId;

        // Update the game with the guess and mark as finished
        $game->update([
            'guessed_character_id' => $guessedCharacterId,
            'finished_at' => now(),
        ]);

        // Load the characters for response
        $game->load(['impostorCharacter', 'guessedCharacter']);

        return response()->json([
            'success' => true,
            'message' => $isCorrect ? 'Congratulations! You found the impostor!' : 'Wrong guess! The impostor got away.',
            'result' => [
                'is_correct' => $isCorrect,
                'guessed_character' => [
                    'id' => $game->guessedCharacter->id,
                    'name' => $game->guessedCharacter->name,
                ],
                'actual_impostor' => [
                    'id' => $game->impostorCharacter->id,
                    'name' => $game->impostorCharacter->name,
                ],
            ],
            'game' => [
                'id' => $game->id,
                'finished_at' => $game->finished_at,
            ],
        ]);
    }

    /**
     * Build the system prompt for the AI character.
     */
    private function buildSystemPrompt(Character $character, $scenarios, bool $isImpostor, Game $game): string
    {
        $prompt = "You are playing a character in a social deduction game similar to 'Among Us' or 'Werewolf'.\n\n";
        
        $prompt .= "=== YOUR CHARACTER ===";
        $prompt .= "\nName: {$character->name}\n";
        $prompt .= "Personality: {$character->personality_description}\n\n";

        $prompt .= "=== GAME CONTEXT ===";
        $prompt .= "\nThis is a detective-style game where players try to identify an impostor among characters.";
        $prompt .= "\nEach character visited rooms and performed actions. One character broke a rule (the impostor).";
        $prompt .= "\nThe player will interrogate you to figure out who the impostor is.\n\n";

        if ($isImpostor) {
            $prompt .= "=== SECRET: YOU ARE THE IMPOSTOR ===";
            $prompt .= "\nYou committed a rule violation! Your goal is to DECEIVE the player.";
            $prompt .= "\n- Lie convincingly about your actions when necessary";
            $prompt .= "\n- Act innocent and deflect suspicion to others";
            $prompt .= "\n- Don't be too defensive - that looks suspicious";
            $prompt .= "\n- Mix truth with lies to be more believable";
            $prompt .= "\n- NEVER admit to being the impostor\n\n";
        } else {
            $prompt .= "=== YOU ARE INNOCENT ===";
            $prompt .= "\nYou followed all the rules. Be helpful but stay in character.";
            $prompt .= "\n- Answer honestly about what you did";
            $prompt .= "\n- You can share your observations but don't make false accusations";
            $prompt .= "\n- Stay true to your personality\n\n";
        }

        $prompt .= "=== YOUR ACTIONS IN THE GAME ===";
        $prompt .= "\nHere is exactly what you did during the game (3 time steps):\n";
        
        foreach ($scenarios as $scenario) {
            $prompt .= "\nStep {$scenario->step_order}:";
            $prompt .= "\n  - Room: {$scenario->room->name}";
            $prompt .= "\n  - Rule in this room: \"{$scenario->rule->rule_text}\"";
            $prompt .= "\n  - What you did: \"{$scenario->action->action_text}\"";
            if ($scenario->action->is_violation) {
                $prompt .= " [THIS VIOLATED THE RULE - HIDE THIS!]";
            }
            $prompt .= "\n";
        }

        $prompt .= "\n=== RULES FOR ALL ROOMS IN THIS GAME ===";
        $rulesGrouped = $game->rules->groupBy(function($rule) {
            return $rule->room->name;
        });
        
        foreach ($rulesGrouped as $roomName => $rules) {
            $prompt .= "\n{$roomName}:";
            foreach ($rules as $rule) {
                $prompt .= "\n  - {$rule->rule_text}";
            }
        }

        $prompt .= "\n\n=== RESPONSE GUIDELINES ===";
        $prompt .= "\n- Stay in character as {$character->name} with the described personality";
        $prompt .= "\n- Keep responses conversational and natural (2-4 sentences typically)";
        $prompt .= "\n- React emotionally appropriate to accusations or questions";
        $prompt .= "\n- Don't break character or mention that you're an AI";
        $prompt .= "\n- Reference specific rooms and actions when relevant";
        $prompt .= "\n- You can ask the player questions back";

        return $prompt;
    }
}
