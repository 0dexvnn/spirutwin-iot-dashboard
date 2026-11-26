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
        Schema::create('sensor_readings', function (Blueprint $table) {
            $table->id();
            $table->string('kolam_id', 20);
            $table->string('kolam_nama', 100);
            $table->timestamp('recorded_at');
            $table->decimal('ph', 5, 2);
            $table->decimal('suhu', 5, 2);
            $table->unsignedInteger('cahaya');
            $table->decimal('turbidity', 7, 2);
            $table->unsignedInteger('tds');
            $table->decimal('biomassa', 7, 2);
            $table->timestamps();

            $table->index(['kolam_id', 'recorded_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensor_readings');
    }
};
