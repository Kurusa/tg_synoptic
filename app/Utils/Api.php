<?php

namespace App\Utils;

use TelegramBot\Api\{
    BotApi,
    Exception,
    InvalidArgumentException,
    Types\Message,
};

class Api extends BotApi
{

    public function __construct($token, $trackerToken = null)
    {
        parent::__construct($token, $trackerToken);
    }

    public function sendMessageWithKeyboard(
        int $chatId,
        string $text,
        $keyboard,
        int $replyToMessageId = null,
    ): Message {
        try {
            return $this->sendMessage($chatId, $text, 'HTML', true, $replyToMessageId, $keyboard);
        } catch (InvalidArgumentException | Exception $e) {
            error_log($e);
        }
    }

}
