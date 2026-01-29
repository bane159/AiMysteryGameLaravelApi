<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rule extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'rule_text',
    ];

    /**
     * Get the room that owns this rule.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get the actions for this rule.
     */
    public function actions(): HasMany
    {
        return $this->hasMany(RuleAction::class);
    }

    /**
     * Get the character scenarios for this rule.
     */
    public function characterScenarios(): HasMany
    {
        return $this->hasMany(CharacterScenario::class);
    }

    /**
     * Get the games that have this rule.
     */
    public function games(): BelongsToMany
    {
        return $this->belongsToMany(Game::class)->withTimestamps();
    }
}
