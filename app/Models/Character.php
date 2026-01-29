<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Character extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'personality_description',
    ];

    /**
     * Get the games where this character is the impostor.
     */
    public function gamesAsImpostor(): HasMany
    {
        return $this->hasMany(Game::class, 'impostor_character_id');
    }

    /**
     * Get the character scenarios for this character.
     */
    public function characterScenarios(): HasMany
    {
        return $this->hasMany(CharacterScenario::class);
    }

    /**
     * Get the conversations for this character.
     */
    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }
}
