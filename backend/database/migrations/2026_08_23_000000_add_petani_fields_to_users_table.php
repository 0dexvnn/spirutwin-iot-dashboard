<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 50)->nullable()->unique()->after('name');
            $table->string('phone', 30)->nullable()->after('username');
            $table->text('address')->nullable()->after('phone');
            $table->string('petani_code', 20)->nullable()->unique()->after('address');
            $table->string('role', 20)->default('petani')->after('petani_code');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropUnique(['petani_code']);
            $table->dropColumn(['username', 'phone', 'address', 'petani_code', 'role']);
        });
    }
};
