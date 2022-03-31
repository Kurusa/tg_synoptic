<?php

namespace App\Services\WeatherService\Properties;

use App\Utils\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class WeeklyService
{

    // TODO: rewrite entire class

    /**
     * @param string $city_name
     * @param $weatherData
     * @param int $start_date
     * @param int $end_date
     * @param bool $full_weather
     * @return mixed
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function getTemplate(string $city_name, $weatherData, int $start_date, int $end_date, bool $full_weather = false)
    {
        $week_array = [
            'Monday' => 'Пн',
            'Tuesday' => 'Вт',
            'Wednesday' => 'Ср',
            'Thursday' => 'Чт',
            'Friday' => 'Пт',
            'Saturday' => 'Сб',
            'Sunday' => 'Нд',
        ];
        $month_array = [
            1 => 'січня',
            2 => 'лютого',
            3 => 'березня',
            4 => 'квітня',
            5 => 'травня',
            6 => 'червня',
            7 => 'липня',
            8 => 'серпня',
            9 => 'вересеня',
            10 => 'жовтеня',
            11 => 'листопада',
            12 => 'груденя',
        ];
        $weather_callback = json_decode($weatherData['owm_callback'], true);

        $result = [];
        $result['sinoptik'] = json_decode($weatherData['sinoptik_callback'], true);
        $result['sunset'] = date('H:i', $weather_callback['city']['sunset']);
        $result['sunrise'] = date('H:i', $weather_callback['city']['sunrise']);
        $result['city'] = $city_name;
        $result['date'] = $week_array[date('l', $start_date)] . ', ' . date('d', $start_date) . ' ' . $month_array[date('n', $start_date)];

        $temperatures = [];
        foreach ($weather_callback['list'] as $key => $weather_item) {
            if ($weather_item['dt'] >= $start_date && $weather_item['dt'] <= $end_date) {
                $temperatures[] = round($weather_item['main']['temp']);

                $icons = $this->getProperty($weather_item['weather'][0]['id'])['icon'];
                $rand_key = array_rand($icons, 1);
                $result['data'][$key]['icon'] = $icons[$rand_key] ?: '';
                $result['data'][$key]['date'] = date('H:i', $weather_item['dt']);
                $result['data'][$key]['temp'] = round($weather_item['main']['temp']);
                $result['data'][$key]['desc'] = $weather_item['weather'][0]['description'];

                $result['data'][$key]['wind_speed'] = round($weather_item['wind']['speed']);
                $result['data'][$key]['clouds'] = $weather_item['clouds']['all'];
                $result['data'][$key]['pressure'] = $weather_item['main']['pressure'];
            }
        }
        $result['min'] = min($temperatures);
        $result['max'] = max($temperatures);

        $twig = Twig::getInstance();
        $template = $twig->load('day_forecast.twig');
        $text = include(__DIR__ . '/../../../config/texts.php');

        foreach ($result['data'] as $key => $item) {
            foreach ($result['sinoptik'] as $sinoptik) {
                if (isset($sinoptik['time'])) {
                    if ($sinoptik['time'] == $item['date']) {
                        $result['data'][$key]['desc'] = $sinoptik['desc'];
                    }
                }
            }
        }

        return $template->render([
            'text' => $text,
            'weather' => $result,
            'full_weather' => $full_weather
        ]);
    }

    protected function getProperty(int $weatherId): array
    {
        $weatherProperties = include(__DIR__ . '/../../../config/weather_properties.php');
        return $weatherProperties[$weatherId];
    }

}
