<?php

namespace App\Contracts;

interface TelegramBotApiContract
{

    /**
     * @param string $chatId
     * @param string $token
     * @param string $message
     * @return void
     */
    public static function sendMessage(string $chatId, string $token, string $message): bool;
}
