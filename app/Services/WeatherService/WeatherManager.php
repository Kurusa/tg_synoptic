<?php

namespace App\Services\WeatherService;

use JetBrains\PhpStorm\Pure;

class WeatherManager {

    const WEATHER_MODE_WEEKLY = 'WEEKLY';
    const WEATHER_MODE_CURRENT = 'CURRENT';
    const WEATHER_MODE_COO = 'COO';
    const WEATHER_MODE_INLINE_QUERY = 'WEATHER_MODE_INLINE_QUERY';

    public DatabaseWeatherHandler $databaseWeatherHandler;

    #[Pure] public function __construct()
    {
        $this->databaseWeatherHandler = new DatabaseWeatherHandler();
    }

    /**
     * @param int $cityId
     * @param $date
     * @return array
     */
    public function getWeeklyWeather(int $cityId, $date): array
    {
        return $this->databaseWeatherHandler->getWeather($cityId, static::WEATHER_MODE_WEEKLY, $date);
    }

    /**
     * @param $coo
     * @return array
     */
    public function getWeatherByCoo($coo): array
    {
        return $this->databaseWeatherHandler->getWeather($coo, static::WEATHER_MODE_COO);
    }

    /**
     * @param int $cityId
     * @return array
     */
    public function getCurrentWeather(int $cityId): array
    {
        return $this->databaseWeatherHandler->getWeather($cityId, static::WEATHER_MODE_CURRENT);
    }

    /**
     * @param int $cityId
     * @return array
     */
    public function getInlineQueryWeather(int $cityId): array
    {
        return $this->databaseWeatherHandler->getWeather($cityId, static::WEATHER_MODE_CURRENT);
    }

}
