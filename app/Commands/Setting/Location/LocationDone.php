<?php

namespace App\Commands\Setting\Location;

use App\Commands\BaseCommand;
use App\Commands\MainMenu;
use App\Models\City;
use Illuminate\Database\QueryException;

class LocationDone extends BaseCommand
{

    function processCommand($param = null)
    {
        if ($this->update->getCallbackQuery()) {
            $districtId = $this->update->getCallbackQueryByKey('d_id');
            $cityId = $this->update->getCallbackQueryByKey('id');

            try {
                $this->user->cities()->create([
                    'district_id' => $districtId,
                    'city_id'     => $cityId,
                ]);
            } catch (QueryException $e) {
                return $this->getBot()->sendMessage(
                    $this->user->chat_id,
                    'Error occurred',
                );
            }

            $this->getBot()->deleteMessage(
                $this->user->chat_id,
                $this->update->getCallbackQuery()->getMessage()->getMessageId(),
            );
        } else {
            // Here are two cases. First is when city is selected from list,
            // Second is when user select specified city
            $exploded = explode(',', $this->update->getMessage()->getText());

            $city = City::where('title', $exploded[0] ?: $this->update->getMessage()->getText())->first();
            if (!$city->count()) {
                $this->notifyAboutCantFindCity();
            }
            if ($this->user->draftCityEntity()) {
                $this->user->draftCityEntity()->update([
                    'city_id' => $city->id,
                ]);
            } else {
                try {
                    $this->user->cities()->create([
                        'district_id' => $city->district->id,
                        'city_id'     => $city->id,
                    ]);
                } catch (QueryException $e) {
                    return $this->getBot()->sendMessage(
                        $this->user->chat_id,
                        'Error occurred',
                    );
                }
            }
        }

        $this->getBot()->sendMessage(
            $this->user->chat_id,
            $this->text['saved_your_city'],
        );
        $this->triggerCommand(MainMenu::class);
    }

}
