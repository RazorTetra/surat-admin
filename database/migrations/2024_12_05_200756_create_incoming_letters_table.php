<?php

// database/migrations/2024_03_06_000001_create_incoming_letters_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incoming_letters', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique();
            $table->string('origin');
            $table->string('sender');
            $table->string('recipient');
            $table->string('subject');
            $table->text('description')->nullable();
            $table->date('letter_date');
            $table->date('received_date');
            $table->enum('type', ['official', 'personal', 'confidential']);
            $table->enum('priority', ['high', 'medium', 'low'])->default('medium');
            $table->enum('status', ['received', 'processed', 'completed'])->default('received');
            $table->text('notes')->nullable();
            $table->foreignId('received_by')->constrained('users');
            $table->foreignId('processed_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incoming_letters');
    }
};