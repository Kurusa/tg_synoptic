<?php

namespace App\Commands\Setting;

use App\Commands\BaseCommand;
use App\Services\Status\UserStatus;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class CityMenu extends BaseCommand
{

    function processCommand($param = null)
    {
        $this->user->update([
            'status' => UserStatus::CITY_MENU,
        ]);

        $this->getBot()->sendMessageWithKeyboard(
            $this->user->chat_id,
            $this->text['city_list_info'],
            new ReplyKeyboardMarkup([
                [$this->text['add_city'], $this->text['my_cities']],
                [$this->text['back']]
            ], false, true)
        );
    }

}
