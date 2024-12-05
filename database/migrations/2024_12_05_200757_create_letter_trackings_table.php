<?php
// database/migrations/2024_03_06_000005_create_letter_tracking_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('letter_tracking', function (Blueprint $table) {
            $table->id();
            $table->morphs('trackable');
            $table->string('status');
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('letter_tracking');
    }
};
