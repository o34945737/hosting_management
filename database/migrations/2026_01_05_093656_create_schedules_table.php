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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('host_id');
            $table->foreignId('studio_id');
            $table->foreignId('brand_id');
            $table->foreignId('telco_id');
            $table->foreignId('ka_id');
            $table->time('start_time');
            $table->time('end_time');
            $table->emum('planned', 'ongoing', 'done', 'canceled');
            $table->text('notes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
