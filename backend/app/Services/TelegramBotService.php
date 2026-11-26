<?php

namespace App\Services;

use App\Models\SensorReading;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class TelegramBotService
{
    public function synchronizeUser(array $from, ?array $chat): User
    {
        $telegramUserId = (string) $from['id'];
        $now = now();

        return DB::transaction(function () use ($from, $chat, $telegramUserId, $now): User {
            $user = User::where('telegram_user_id', $telegramUserId)->lockForUpdate()->first();
            $displayName = trim(($from['first_name'] ?? '').' '.($from['last_name'] ?? '')) ?: ($user?->name ?: 'Petani Telegram');
            $profile = [
                'name' => $displayName,
                'telegram_chat_id' => isset($chat['id']) ? (string) $chat['id'] : $user?->telegram_chat_id,
                'telegram_username' => $from['username'] ?? null,
                'telegram_name' => $displayName,
                'telegram_first_name' => $from['first_name'] ?? null,
                'telegram_last_name' => $from['last_name'] ?? null,
                'telegram_last_seen_at' => $now,
                'telegram_connection_status' => 'connected',
            ];

            if ($user) {
                $user->update($profile);

                return $user;
            }

            return User::create(array_merge($profile, [
                'username' => 'telegram_'.$telegramUserId,
                'email' => 'telegram-'.$telegramUserId.'@petani.spirutwin.local',
                'password' => Str::random(40),
                'role' => 'petani',
                'telegram_user_id' => $telegramUserId,
                'telegram_connected_at' => $now,
            ]));
        });
    }

    public function welcomeMessage(User $user): string
    {
        $name = $user->telegram_first_name ?: $user->name;
        $kolams = $user->kolams()->orderBy('code')->get();
        if ($kolams->isEmpty()) {
            return "🌿 SPIRUTWIN Monitoring\n\nHalo, {$name}.\n\nSelamat datang di SPIRUTWIN Monitoring.\n\nAkun Telegram kamu berhasil terhubung.\n\nPenempatan kolam:\nBelum ditentukan oleh admin.\n\nSilakan tunggu penempatan dari admin.";
        }

        return "🌿 SPIRUTWIN Monitoring\n\nHalo, {$name}.\n\nAkun kamu berhasil terhubung.\n\nKolam yang menjadi tanggung jawabmu:\n".$kolams->map(fn ($kolam) => "🟢 {$kolam->name}")->join("\n")."\n\nSilakan gunakan tombol di bawah untuk melihat kondisi terbaru.";
    }

    public function conditionMessage(User $user): string
    {
        $kolams = $user->kolams()->orderBy('code')->get();
        if ($kolams->isEmpty()) {
            return 'Tidak ada kolam yang ditugaskan ke akun kamu saat ini.';
        }

        $messages = $kolams->map(function ($kolam) {
            $reading = SensorReading::where('kolam_id', $kolam->code)->latest('recorded_at')->first();
            if (! $reading) {
                return "🟢 {$kolam->name}\nBelum ada data sensor terbaru.";
            }

            return "🟢 {$kolam->name}\n"
                ."pH: {$reading->ph}\nSuhu: {$reading->suhu}°C\n"
                ."Cahaya: {$reading->cahaya} lux\nKekeruhan: {$reading->turbidity}\n"
                ."TDS: {$reading->tds} ppm\nBiomassa: {$reading->biomassa}\n"
                .'Diperbarui: '.$reading->recorded_at->timezone(config('app.timezone'))->format('d-m-Y H:i');
        });

        return "🌿 Kondisi Saat Ini\n\n".$messages->join("\n\n");
    }

    public function sendMessage(string $chatId, string $text, bool $withConditionButton = false): void
    {
        $token = config('services.telegram.bot_token');
        if (! $token) {
            logger()->warning('Telegram message was not sent because TELEGRAM_BOT_TOKEN is not configured.');

            return;
        }

        $payload = ['chat_id' => $chatId, 'text' => $text];
        if ($withConditionButton) {
            $payload['reply_markup'] = ['inline_keyboard' => [[['text' => '🔄 Cek Kondisi Saat Ini', 'callback_data' => 'check_condition']]]];
        }
        Http::post("https://api.telegram.org/bot{$token}/sendMessage", $payload);
    }

    public function answerCallback(string $callbackId): void
    {
        $token = config('services.telegram.bot_token');
        if ($token) {
            Http::post("https://api.telegram.org/bot{$token}/answerCallbackQuery", ['callback_query_id' => $callbackId]);
        }
    }
}
