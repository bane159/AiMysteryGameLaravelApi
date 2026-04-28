<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelSeeder extends Seeder
{
    /**
     * Seed levels 1-55 with exponentially increasing XP requirements.
     *
     * Formula: xp_required = round(100 * level^1.5)
     * Examples:
     *   Level 1  -> 100 XP
     *   Level 5  -> 558 XP
     *   Level 10 -> 1581 XP
     *   Level 25 -> 6250 XP
     *   Level 55 -> 40764 XP
     */
    public function run(): void
    {
        $levels = [];

        for ($i = 1; $i <= 55; $i++) {
            $levels[] = [
                'level'        => $i,
                'xp_required'  => (int) round(100 * pow($i, 1.5)),
            ];
        }

        DB::table('levels')->insertOrIgnore($levels);
    }
}
