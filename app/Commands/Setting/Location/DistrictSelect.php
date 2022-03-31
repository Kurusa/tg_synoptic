<?php

namespace App\Commands\Setting\Location;

use App\Commands\BaseCommand;
use App\Services\LocationSearch\DistrictList;
use App\Services\Status\UserStatus;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class DistrictSelect extends BaseCommand
{

    function processCommand($param = null)
    {
        $this->user->update([
            'status' => UserStatus::SETTINGS_DISTRICT_SELECT,
        ]);

        $buttons = DistrictList::getDistrictButtons();
        $buttons[] = [$this->text['back']];

        $this->getBot()->sendMessageWithKeyboard(
            $this->user->chat_id,
            $this->text['select_district'],
            new ReplyKeyboardMarkup($buttons, false, true),
        );
    }

}
