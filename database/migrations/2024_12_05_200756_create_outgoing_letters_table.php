<?php
// database/migrations/2024_03_06_000002_create_outgoing_letters_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('outgoing_letters', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique();
            $table->string('recipient');
            $table->string('recipient_address');
            $table->string('subject');
            $table->text('content');
            $table->date('letter_date');
            $table->enum('type', ['official', 'personal', 'confidential']);
            $table->enum('priority', ['high', 'medium', 'low'])->default('medium');
            $table->enum('status', ['draft', 'review', 'approved', 'sent'])->default('draft');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outgoing_letters');
    }
};
