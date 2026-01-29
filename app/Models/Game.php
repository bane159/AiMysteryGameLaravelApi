<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'ai_model_id',
        'impostor_character_id',
        'guessed_character_id',
        'finished_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    /**
     * Get the user that owns this game.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the AI model used in this game.
     */
    public function aiModel(): BelongsTo
    {
        return $this->belongsTo(AiModel::class);
    }

    /**
     * Get the impostor character for this game.
     */
    public function impostorCharacter(): BelongsTo
    {
        return $this->belongsTo(Character::class, 'impostor_character_id');
    }

    /**
     * Get the guessed character for this game.
     */
    public function guessedCharacter(): BelongsTo
    {
        return $this->belongsTo(Character::class, 'guessed_character_id');
    }

    /**
     * Get the character scenarios for this game.
     */
    public function characterScenarios(): HasMany
    {
        return $this->hasMany(CharacterScenario::class);
    }

    /**
     * Get the conversations for this game.
     */
    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }

    /**
     * Get the rules generated for this game.
     */
    public function rules(): BelongsToMany
    {
        return $this->belongsToMany(Rule::class)->withTimestamps();
    }
}
