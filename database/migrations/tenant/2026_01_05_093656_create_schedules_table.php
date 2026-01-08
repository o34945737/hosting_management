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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('host_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('studio_id')->constrained('studios')->restrictOnDelete();
            $table->foreignId('brand_id')->constrained('brands')->restrictOnDelete();

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->dateTime('start_at');
            $table->dateTime('end_at');

            $table->enum('status', ['planned', 'ongoing', 'done', 'canceled'])->default('ongoing');
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['host_id', 'start_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::enableForeignKeyConstraints();
        Schema::dropIfExists('schedules');
    }
};
