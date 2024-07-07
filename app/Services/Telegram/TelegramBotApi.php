<?php

namespace App\Services\Telegram;

use App\Contracts\TelegramBotApiContract;
use App\Exceptions\TelegramBotException;
use Illuminate\Support\Facades\Http;

class TelegramBotApi implements TelegramBotApiContract
{
    public const TELEGRAM_API_HOST = 'https://api.telegram.org/bot';

    public static function sendMessage(string $chatId, string $token, string $message): bool
    {
        try {
            $response = Http::get(self::TELEGRAM_API_HOST . $token . '/sendMessage', [
                'chat_id' => $chatId,
                'text' => $message
            ]);

            if (!$response->successful()) {
                throw new TelegramBotException('Telegram bot is not available. Error message for telegram bot: ' . $message);
            }
            return true;
        } catch (TelegramBotException $e) {
            logger()->channel('single')->error($e->getMessage());
            return false;
        }
    }
}
