<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('petani_kolam', function (Blueprint $table) {
            $table->id();
            $table->foreignId('petani_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('kolam_id')->constrained('kolams')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['petani_id', 'kolam_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('petani_kolam');
    }
};
