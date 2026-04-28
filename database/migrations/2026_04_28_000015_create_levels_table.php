<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('levels', function (Blueprint $table) {
            $table->unsignedTinyInteger('level')->primary();
            $table->unsignedInteger('xp_required');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('levels');
    }
};
