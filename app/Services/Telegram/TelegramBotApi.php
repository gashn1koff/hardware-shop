<?php

namespace App\Services\Telegram;

use App\Contracts\TelegramBotApiContract;
use Illuminate\Support\Facades\Http;

class TelegramBotApi implements TelegramBotApiContract
{
    public const TELEGRAM_API_HOST = 'https://api.telegram.org/bot';

    public static function sendMessage(string $chatId, string $token, string $message): void
    {
        Http::get(self::TELEGRAM_API_HOST . $token . '/sendMessage', [
            'chat_id' => $chatId,
            'text' => $message
        ]);
    }
}
