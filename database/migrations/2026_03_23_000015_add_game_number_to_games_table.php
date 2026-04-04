<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->unsignedInteger('game_number')->nullable()->after('id');
        });

        $userIds = DB::table('games')
            ->select('user_id')
            ->distinct()
            ->pluck('user_id');

        foreach ($userIds as $userId) {
            $gameNumber = 1;

            $games = DB::table('games')
                ->where('user_id', $userId)
                ->orderBy('created_at')
                ->orderBy('id')
                ->get(['id']);

            foreach ($games as $game) {
                DB::table('games')
                    ->where('id', $game->id)
                    ->update(['game_number' => $gameNumber]);

                $gameNumber++;
            }
        }

        Schema::table('games', function (Blueprint $table) {
            $table->unique(['user_id', 'game_number'], 'games_user_game_number_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Must drop the FK first — MySQL uses the composite unique index as the
        // backing index for the user_id FK, so it refuses to drop it directly.
        Schema::table('games', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropUnique('games_user_game_number_unique');
        });

        Schema::table('games', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->dropColumn('game_number');
        });
    }
};
