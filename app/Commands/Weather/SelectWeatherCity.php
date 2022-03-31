<?php

namespace App\Commands\Weather;

use App\Commands\BaseCommand;
use App\Models\City;
use App\Services\LocationSearch\TgLocation;
use App\Services\Status\UserStatus;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class SelectWeatherCity extends BaseCommand
{

    function processCommand($param = null)
    {
        if ($this->user->status === UserStatus::FORECAST_CITY_SELECT || $this->user->status === UserStatus::CURRENT_CITY_SELECT) {
            // если пользователь выбрал город со списка, а не отправил локацию
            if ($this->update->getMessage()->getText()) {
                $exploded = explode(',', $this->update->getMessage()->getText());

                $city = City::where('title', $exploded[0] ?: $this->update->getMessage()->getText())->first();
                if ($this->user->status === UserStatus::FORECAST_CITY_SELECT) {
                    $this->triggerCommand(WeatherLess::class, $city->id);
                } else {
                    $this->triggerCommand(CurrentWeatherLess::class, $city->id);
                }
            } elseif ($this->update->getMessage()->getLocation()) {
                $buttons = TgLocation::getSearchResult($this->update->getMessage()->getLocation());
                if ($buttons) {
                    $this->selectingLocationFlow($buttons);
                } else {
                    $this->notifyAboutCantFindCity();
                }
            }
        } else {
            $this->user->update([
                'status' => $this->update->getMessage()->getText() == $this->text['current_weather'] ? UserStatus::CURRENT_CITY_SELECT : UserStatus::FORECAST_CITY_SELECT,
            ]);

            foreach ($this->user->cities as $city) {
                $buttons[] = [[$city->full_title]];
            }

            $buttons[] = [['text' => $this->text['send_location_type'], 'request_location' => true]];
            $buttons[] = [$this->text['back']];

            $this->getBot()->sendMessageWithKeyboard(
                $this->user->chat_id,
                $this->text['select_city_from_list'],
                new ReplyKeyboardMarkup($buttons, false, true),
            );
        }
    }

}
