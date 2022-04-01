<?php

namespace App\Commands\Setting\Location;

use App\Commands\BaseCommand;
use App\Services\Status\UserStatus;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class CityName extends BaseCommand
{

    function processCommand($param = null)
    {
        if ($this->user->status === UserStatus::SETTINGS_CITY_NAME) {
            $buttons = \App\Services\LocationSearch\CityName::getSearchResult($this->update->getMessage()->getText());
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
                'status' => UserStatus::SETTINGS_CITY_NAME,
            ]);

            $this->getBot()->sendMessageWithKeyboard(
                $this->user->chat_id,
                $this->text['request_to_write_city'],
                new ReplyKeyboardMarkup([
                    [$this->text['back']]
                ], false, true,
                )
            );
        }
    }

}
