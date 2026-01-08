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
        Schema::create('attendance_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('checkin_open_before_start_minutes')->default(30);
            $table->unsignedInteger('checkin_close_after_start_minutes')->default(15);
            $table->unsignedInteger('checkout_open_after_start_minutes')->default(0);
            $table->unsignedInteger('checkout_close_after_end_minutes')->default(60);
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::enableForeignKeyConstraints();
        Schema::dropIfExists('attendances_settings');
    }
};
