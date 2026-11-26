<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kolams', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->string('name', 100);
            $table->timestamps();
        });

        $now = now();
        DB::table('kolams')->insert([
            ['code' => 'K01', 'name' => 'Kolam 01', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'K02', 'name' => 'Kolam 02', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'K03', 'name' => 'Kolam 03', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'K04', 'name' => 'Kolam 04', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('kolams');
    }
};
