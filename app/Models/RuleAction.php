<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RuleAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'rule_id',
        'action_text',
        'is_violation',
    ];

    protected $casts = [
        'is_violation' => 'boolean',
    ];

    /**
     * Get the rule that owns this action.
     */
    public function rule(): BelongsTo
    {
        return $this->belongsTo(Rule::class);
    }

    /**
     * Get the character scenarios using this action.
     */
    public function characterScenarios(): HasMany
    {
        return $this->hasMany(CharacterScenario::class, 'action_id');
    }
}
