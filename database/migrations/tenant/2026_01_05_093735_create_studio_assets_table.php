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
        Schema::create('studio_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('studio_id')->constrained('studios')->cascadeOnDelete();

            $table->string('name');
            $table->string('asset_type')->nullable();
            $table->string('serial_number')->nullable();
            $table->unsignedInteger('quantity')->default(1);

            $table->enum('asset_condition', ['new', 'good', 'fair', 'poor', 'broken'])->default('good');
            $table->enum('status', ['available', 'in_use', 'maintenance', 'retired'])->default('available');

            $table->string('location_note')->nullable();
            $table->text('notes')->nullable();

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
        Schema::dropIfExists('studio_assets');
    }
};
