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
        Schema::create('attendances_settings', function (Blueprint $table) {
            $table->id();
            $table->string('checkin_open_before_start');
            $table->string('checkin_close_after_start');
            $table->string('checkout_open_after_start');
            $table->string('checkout_close_after_end');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances_settings');
    }
};
