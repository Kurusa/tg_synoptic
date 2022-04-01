<?php

namespace App\Commands;

use App\Services\Status\UserStatus;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class MainMenu extends BaseCommand
{

    function processCommand($param = false)
    {
        $this->user->update([
            'status' => UserStatus::MAIN_MENU,
        ]);

        $this->getBot()->sendMessageWithKeyboard(
            $this->user->chat_id,
            $this->text['mainMenu'],
            new ReplyKeyboardMarkup([
                [$this->text['forecast']],
                [$this->text['current_weather']],
                [$this->text['feedback'], $this->text['change_city']],
            ], false, true),
        );
    }

}
