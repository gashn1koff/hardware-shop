<?php

namespace App\Logging\Telegram;

use App\Contracts\TelegramBotApiContract;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Monolog\LogRecord;

class TelegramLoggerHandler extends AbstractProcessingHandler
{

    private string $chatId;
    private string $token;

    public function __construct(array $config)
    {
        $level = Logger::toMonologLevel($config['level']);
        $this->chatId = $config['chat_id'];
        $this->token = $config['token'];

        parent::__construct($level);

    }

    protected function write(LogRecord $record): void
    {
        app(TelegramBotApiContract::class)::sendMessage($this->chatId, $this->token, $record->formatted);
    }
}
