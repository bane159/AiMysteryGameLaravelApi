<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AiModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'provider',
        'context_length',
        'notes',
    ];

    protected $casts = [
        'context_length' => 'integer',
    ];

    /**
     * Get the games using this AI model.
     */
    public function games(): HasMany
    {
        return $this->hasMany(Game::class);
    }
}
