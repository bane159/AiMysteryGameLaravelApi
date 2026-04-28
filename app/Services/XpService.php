<?php

namespace App\Services;

use App\Models\Level;
use App\Models\User;
use App\Models\XpTransaction;
use Illuminate\Support\Facades\Cache;

class XpService
{
    public const MAX_LEVEL = 55;

    /**
     * XP awarded per action type and difficulty.
     */
    private const XP_TABLE = [
        'question'       => ['Easy' => 5,  'Normal' => 10, 'Hard' => 15],
        'game_complete'  => ['Easy' => 20, 'Normal' => 40, 'Hard' => 60],
        'impostor_bonus' => ['Easy' => 30, 'Normal' => 50, 'Hard' => 80],
    ];

    /**
     * Award XP to a user, log the transaction, and handle level-ups.
     * Returns the number of XP awarded.
     */
    public function award(User $user, string $type, ?int $gameId, string $difficulty): int
    {
        $xpAmount = $this->calculateXp($type, $difficulty);

        if ($xpAmount <= 0) {
            return 0;
        }

        XpTransaction::create([
            'user_id'    => $user->id,
            'game_id'    => $gameId,
            'type'       => $type,
            'xp_amount'  => $xpAmount,
        ]);

        $this->addXpAndLevelUp($user, $xpAmount);

        return $xpAmount;
    }

    /**
     * Add XP to the user and process any level-ups.
     * Excess XP carries over into the next level.
     * At MAX_LEVEL, XP still accumulates (no cap) but no further level-ups occur.
     */
    private function addXpAndLevelUp(User $user, int $amount): void
    {
        $user->xp += $amount;

        while ($user->level < self::MAX_LEVEL) {
            $required = $this->getXpRequired($user->level);

            if ($user->xp < $required) {
                break;
            }

            $user->xp    -= $required;
            $user->level += 1;
        }

        $user->save();
    }

    /**
     * Calculate XP for a given action type and difficulty.
     */
    public function calculateXp(string $type, string $difficulty): int
    {
        return self::XP_TABLE[$type][$difficulty] ?? 0;
    }

    /**
     * Return the XP required to advance from the given level to the next.
     * Cached to avoid repeated DB queries.
     */
    public function getXpRequired(int $level): int
    {
        return Cache::rememberForever("level_xp_{$level}", function () use ($level) {
            return (int) (Level::find($level)?->xp_required ?? PHP_INT_MAX);
        });
    }

    /**
     * Build a progress summary for the user to include in API responses.
     *
     * Returns:
     *   level          - current level (1-55)
     *   xp             - current XP within this level
     *   xp_required    - XP needed to reach the next level (null at max level)
     *   xp_percentage  - progress percentage within the current level (0-100)
     *   is_max_level   - true when the user has reached level 55
     */
    public function progressSummary(User $user): array
    {
        $isMax      = $user->level >= self::MAX_LEVEL;
        $xpRequired = $isMax ? null : $this->getXpRequired($user->level);

        return [
            'level'         => $user->level,
            'xp'            => $user->xp,
            'xp_required'   => $xpRequired,
            'xp_percentage' => $isMax ? 100 : ($xpRequired > 0 ? (int) floor(($user->xp / $xpRequired) * 100) : 0),
            'is_max_level'  => $isMax,
        ];
    }
}
