<?php

namespace App\Commands\Setting\Location;

use App\Commands\BaseCommand;
use App\Services\Status\UserStatus;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class SelectLocationType extends BaseCommand
{

    function processCommand($param = null)
    {
        $this->user->update([
            'status' => UserStatus::SETTINGS_LOCATION_TYPE_SELECT,
        ]);

        $buttons[] = [$this->text['send_location_type']];
        $buttons[] = [$this->text['choose_city_from_list']];
        $buttons[] = [$this->text['send_city_name']];
        $buttons[] = [$this->text['back']];

        $this->getBot()->sendMessageWithKeyboard(
            $this->user->chat_id,
            $this->text['choose_city_question'],
            new ReplyKeyboardMarkup($buttons, false, true),
        );
    }

}
