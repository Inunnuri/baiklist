<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users');
            $table->string('title');
            $table->text('description')->nullable();
            $table->datetime('due_date')->nullable();
            $table->datetime('reminder_at')->nullable();
            $table->foreignId('frequency_id')->nullable()->constrained('frequencies')->onDelete('set null');
            $table->foreignId('category_id')
            ->nullable()
            ->constrained('categories')
            ->onDelete('set null');
            $table->foreignId('status_id')
            ->nullable()
            ->constrained('statuses')
            ->onDelete('set null');
            $table->foreignId('calendar_id')
            ->nullable()
            ->constrained('calendars')
            ->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};