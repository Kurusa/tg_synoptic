<?php

namespace App\Commands;

use App\Utils\Api;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;
use TelegramBot\Api\Types\Update;

class BasicMessages
{
    private $bot;

    protected $botUser;

    public function __construct(Update $update)
    {
        $this->update = $update;
        if ($update->getCallbackQuery()) {
            $this->botUser = $update->getCallbackQuery()->getFrom();
        } elseif ($update->getMessage()) {
            $this->botUser = $update->getMessage()->getFrom();
        } elseif ($update->getInlineQuery()) {
            $this->botUser = $update->getInlineQuery()->getFrom();
        } else {
            throw new \Exception('cant get telegram user data');
        }
    }

    public function getBot(): Api
    {
        if (!$this->bot) {
            $this->bot = new Api(env('TELEGRAM_BOT_TOKEN'));
        }

        return $this->bot;
    }

    function notifyAboutNewUser() {
        $this->getBot()->sendMessage(
            375036391,
            '<b>Новий користувач:</b> @' . $this->botUser->getUsername() . ', ' . $this->botUser->getFirstName() ?: '',
            'html',
        );
    }

    function notifyAboutCantFindCity() {
        $this->getBot()->sendMessage(
            $this->user->chat_id,
            $this->text['cant_find_city'],
        );
    }

    function selectingLocationFlow(array $buttons) {
        $buttons[] = [$this->text['back']];

        $this->getBot()->sendMessageWithKeyboard(
            $this->user->chat_id,
            $this->text['did_you_mean_this_city'],
            new ReplyKeyboardMarkup(
                $buttons,
                false, true,
            ),
        );
    }

    function sendMessageWithBackButton(string $text) {
        $this->getBot()->sendMessageWithKeyboard(
            $this->user->chat_id,
            $text,
            new ReplyKeyboardMarkup(
                [[$this->text['back']]],
                false, true,
            )
        );
    }

    function testMessage($text) {
        $this->getBot()->sendMessage(
            $this->user->chat_id,
            $text,
        );
    }

}
