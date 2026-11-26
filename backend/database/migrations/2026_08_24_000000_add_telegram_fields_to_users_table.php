<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('telegram_user_id', 32)->nullable()->unique()->after('role');
            $table->string('telegram_chat_id', 32)->nullable()->after('telegram_user_id');
            $table->string('telegram_username', 255)->nullable()->after('telegram_chat_id');
            $table->string('telegram_first_name', 255)->nullable()->after('telegram_username');
            $table->string('telegram_last_name', 255)->nullable()->after('telegram_first_name');
            $table->timestamp('telegram_connected_at')->nullable()->after('telegram_last_name');
            $table->timestamp('telegram_last_seen_at')->nullable()->after('telegram_connected_at');
            $table->string('telegram_connection_status', 20)->nullable()->after('telegram_last_seen_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['telegram_user_id']);
            $table->dropColumn(['telegram_user_id', 'telegram_chat_id', 'telegram_username', 'telegram_first_name', 'telegram_last_name', 'telegram_connected_at', 'telegram_last_seen_at', 'telegram_connection_status']);
        });
    }
};
