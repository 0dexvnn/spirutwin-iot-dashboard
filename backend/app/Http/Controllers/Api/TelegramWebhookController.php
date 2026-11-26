<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TelegramBotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TelegramWebhookController extends Controller
{
    public function __invoke(Request $request, TelegramBotService $telegram): JsonResponse
    {
        $secret = config('services.telegram.webhook_secret');
        if ($secret && ! hash_equals($secret, (string) $request->header('X-Telegram-Bot-Api-Secret-Token'))) {
            abort(403);
        }

        $update = $request->validate([
            'message.from.id' => ['nullable'],
            'callback_query.from.id' => ['nullable'],
        ]);

        $message = $request->input('message');
        if (is_array($message) && ($message['text'] ?? null) && preg_match('/^\/start(?:\s|$)/', $message['text'])) {
            $user = $telegram->synchronizeUser($message['from'], $message['chat'] ?? null);
            $telegram->sendMessage($user->telegram_chat_id, $telegram->welcomeMessage($user), true);

            return response()->json(['ok' => true]);
        }

        $callback = $request->input('callback_query');
        if (is_array($callback) && ($callback['data'] ?? null) === 'check_condition') {
            $user = $telegram->synchronizeUser($callback['from'], data_get($callback, 'message.chat'));
            $telegram->answerCallback((string) $callback['id']);
            $telegram->sendMessage($user->telegram_chat_id, $telegram->conditionMessage($user), true);
        }

        return response()->json(['ok' => true]);
    }
}
