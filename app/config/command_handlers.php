<?php

use App\Commands\MainMenu;
use App\Commands\Setting\{CityMenu,
    Location\CityName,
    Location\CitySelect,
    Location\DistrictSelect,
    Location\LocationDone,
    Location\SelectLocationType,
    Location\TgLocation,
    UserCityList};
use App\Commands\Weather\{
    GenerateImage,
    SelectWeatherCity,
    WeatherLess,
    WeatherMore,
};
use App\Commands\Back;
use App\Commands\Feedback;
use App\Services\Status\UserStatus;

return [
    'callback_commands' => [
        'weather_more'      => WeatherMore::class,
        'weather_less'      => WeatherLess::class,

        'weather_next_less' => WeatherLess::class,
        'weather_prev_less' => WeatherLess::class,

        'weather_next_more' => WeatherMore::class,
        'weather_prev_more' => WeatherLess::class,

        'generate_current_image' => GenerateImage::class,

        'next_cities' => CitySelect::class,
        'prev_cities' => CitySelect::class,
        'select_city' => LocationDone::class,
    ],
    
    'keyboard_commands' => [
        'back'            => Back::class,
        'feedback'        => Feedback::class,
        'change_city'     => CityMenu::class,
        'my_cities'       => UserCityList::class,
        'add_city'        => SelectLocationType::class,

        'current_weather' => SelectWeatherCity::class,
        'forecast'        => SelectWeatherCity::class,

        'send_location_type'    => TgLocation::class,
        'choose_city_from_list' => DistrictSelect::class,
        'send_city_name'        => CityName::class,
    ],
    
    'status_commands' => [
        UserStatus::NEW                           => MainMenu::class,
        UserStatus::SETTINGS_LOCATION_WAITING     => TgLocation::class,
        UserStatus::SETTINGS_LOCATION_TYPE_SELECT => SelectLocationType::class,
        UserStatus::SETTINGS_DISTRICT_SELECT      => CitySelect::class,
        UserStatus::SETTINGS_LOCATION_SELECTING   => LocationDone::class,
        UserStatus::SETTINGS_CITY_NAME            => CityName::class,
        UserStatus::DISTRICT_SELECT               => DistrictSelect::class,
        UserStatus::FEEDBACK                      => Feedback::class,
        UserStatus::USER_CITY_LIST                => UserCityList::class,
        UserStatus::FORECAST_CITY_SELECT          => SelectWeatherCity::class,
        UserStatus::CURRENT_CITY_SELECT           => SelectWeatherCity::class,
        UserStatus::CITY_MENU                     => CityMenu::class,
    ],
    
    'slash_commands' => [
        '/start' => MainMenu::class,
    ],
    
];
