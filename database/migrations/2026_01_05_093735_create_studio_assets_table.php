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
        Schema::create('studio_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('studio_id')->constrained();
            $table->string('name');
            $table->string('asset_type');
            $table->string('serial_number');
            $table->string('quantity');
            $table->enum('asset_condition', ['new','good','far','poor','broken']);
            $table->enum('status', ['available', 'in_use', 'maintenance', 'retired']);
            $table->string('location_note');
            $table->text('notes');
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('studio_assets');
    }
};
