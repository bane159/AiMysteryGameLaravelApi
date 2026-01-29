<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CharacterScenario extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'character_id',
        'room_id',
        'rule_id',
        'action_id',
        'step_order',
    ];

    protected $casts = [
        'step_order' => 'integer',
    ];

    /**
     * Get the game that owns this scenario.
     */
    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Get the character for this scenario.
     */
    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class);
    }

    /**
     * Get the room for this scenario.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get the rule for this scenario.
     */
    public function rule(): BelongsTo
    {
        return $this->belongsTo(Rule::class);
    }

    /**
     * Get the action for this scenario.
     */
    public function action(): BelongsTo
    {
        return $this->belongsTo(RuleAction::class, 'action_id');
    }
}
