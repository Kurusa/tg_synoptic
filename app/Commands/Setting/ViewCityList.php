<?php

namespace App\Commands\Setting;

use App\Commands\BaseCommand;
use App\Models\City;
use App\Services\Status\UserStatus;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class ViewCityList extends BaseCommand
{

    function processCommand($param = null)
    {
        if ($this->user->status === UserStatus::USER_CITY_LIST) {
            $exploded = explode(',', $this->update->getMessage()->getText());
            $city = City::where('title', $exploded[0])->first();
            if ($city) {
                $this->user->cities()->where('city_id', $city->id)->delete();
                $this->triggerCommand(CityMenu::class);
            }
        } else {
            $this->user->update([
                'status' => UserStatus::USER_CITY_LIST,
            ]);

            $cities = [];
            foreach ($this->user->cities as $city) {
                $cities[] = [$city->city->full_title];
            }
            $cities[] = [$this->text['back']];

            $this->getBot()->sendMessageWithKeyboard(
                $this->user->chat_id,
                $this->text['my_cities_info'],
                new ReplyKeyboardMarkup($cities, false, true)
            );
        }
    }

}
