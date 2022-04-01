<?php

namespace App\Commands\Setting\Location;

use App\Commands\BaseCommand;
use App\Services\Status\UserStatus;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class TgLocation extends BaseCommand
{

    function processCommand($param = null)
    {
        if ($this->user->status == UserStatus::SETTINGS_LOCATION_WAITING) {
            $buttons = \App\Services\LocationSearch\TgLocation::getSearchResult($this->update->getMessage()->getLocation());
            if ($buttons) {
                $this->user->update([
                    'status' => UserStatus::SETTINGS_LOCATION_SELECTING,
                ]);

                $this->selectingLocationFlow($buttons);
            } else {
                $this->notifyAboutCantFindCity();
            }
        } else {
            $this->user->update([
                'status' => UserStatus::SETTINGS_LOCATION_WAITING,
            ]);

            $this->getBot()->sendMessageWithKeyboard(
                $this->user->chat_id,
                $this->text['send_your_location'],
                new ReplyKeyboardMarkup([
                    [[
                        'text' => $this->text['click'],
                        'request_location' => true,
                    ]],
                    [$this->text['back']]
                ], false, true),
            );
        }
    }

}
