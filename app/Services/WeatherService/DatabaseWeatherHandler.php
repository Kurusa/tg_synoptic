<?php

namespace App\Services\WeatherService;

use App\Models\WeatherCache;
use Carbon\Carbon;

class DatabaseWeatherHandler
{

    // TODO: rewrite cache logic

    const CACHE_TIME = [
        WeatherManager::WEATHER_MODE_WEEKLY       => 90,
        WeatherManager::WEATHER_MODE_COO          => 90,
        WeatherManager::WEATHER_MODE_CURRENT      => 10,
        WeatherManager::WEATHER_MODE_INLINE_QUERY => 10,
    ];

    const OWM_FUNCTION = [
        WeatherManager::WEATHER_MODE_COO          => 'WEATHER',
        WeatherManager::WEATHER_MODE_WEEKLY       => 'FORECAST',
        WeatherManager::WEATHER_MODE_CURRENT      => 'WEATHER',
        WeatherManager::WEATHER_MODE_INLINE_QUERY => 'WEATHER'
    ];

    /**
     * @param $cityId
     * @param string $mode
     * @param null $date
     */
    public function getWeather($cityId, string $mode, $date = null)
    {
        $date = $date ?: Carbon::today();
        $owmApi = new OwmApiService();

        if ($mode == WeatherManager::WEATHER_MODE_COO) {
            return $owmApi->call(strtolower(static::OWM_FUNCTION[$mode]), [
                'lat' => $cityId['latitude'],
                'lon' => $cityId['longitude']
            ]);
        }

        $cache = WeatherCache::where('city_id', $cityId)
            ->whereDate('created_at', Carbon::today())
            ->where('for_date', $date)
            ->where('status', $mode)
            ->first();
        if (!$cache || !$this->isValidData($cache->created_at, static::CACHE_TIME[$mode])) {
            $owm_data = $owmApi->call(static::OWM_FUNCTION[$mode], [
                'id' => $cityId,
            ]);

            $sinoptikCallback = null;
            if ($mode !== WeatherManager::WEATHER_MODE_INLINE_QUERY) {
                $sinoptikCallback = json_encode(ParserSinoptik::parse($cityId, $date->timestamp ?: time()), true);
            }
            WeatherCache::create([
                'city_id'           => $cityId,
                'status'            => $mode,
                'owm_callback'      => json_encode($owm_data, true),
                'sinoptik_callback' => $sinoptikCallback,
                'for_date'          => $date
            ]);
            $cache = WeatherCache::where('city_id', $cityId)
                ->whereDate('created_at', Carbon::today())
                ->where('for_date', $date)
                ->where('status', $mode)
                ->first();
        }

        return $cache;
    }


    protected function isValidData($createdAt, int $cacheTime): bool
    {
        if ($createdAt) {
            $minutes_left = Carbon::now()->diffInMinutes($createdAt);
            return $minutes_left < $cacheTime;
        }

        return false;
    }

}
