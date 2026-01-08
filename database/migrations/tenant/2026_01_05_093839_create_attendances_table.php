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
        Schema::disableForeignKeyConstraints();
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('schedule_id')
                ->constrained('schedules')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->dateTime('checkin_at')->nullable();
            $table->dateTime('checkout_at')->nullable();

            $table->enum('status', ['ontime', 'late', 'absent', 'manual_edit'])
                ->default('ontime');

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique(['schedule_id', 'user_id'], 'attendances_schedule_user_unique');

            $table->index(['user_id', 'checkin_at'], 'attendances_user_checkin_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::enableForeignKeyConstraints();
        Schema::dropIfExists('attendances');
    }
};
