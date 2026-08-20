<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    public function send(string $message): bool
    {
        $botToken = kua_setting('telegram_bot_token');
        $chatId = kua_setting('telegram_chat_id');

        if (empty($botToken) || empty($chatId)) {
            Log::warning('Telegram config belum diisi (TELEGRAM_BOT_TOKEN / TELEGRAM_CHAT_ID)');

            return false;
        }

        try {
            $response = Http::timeout(10)->post(
                "https://api.telegram.org/bot{$botToken}/sendMessage",
                [
                    'chat_id' => $chatId,
                    'text' => $message,
                    'parse_mode' => 'HTML',
                ]
            );

            return $response->successful();
        } catch (\Throwable $e) {
            Log::error('Gagal kirim notifikasi Telegram: ' . $e->getMessage());

            return false;
        }
    }
}
