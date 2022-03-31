<?php

namespace App\Services\WeatherService\Properties;

use App\Utils\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CurrentService
{

    // TODO: rewrite entire class

    /**
     * @param string $city_name
     * @param $weatherData
     * @return mixed
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function getTemplate(string $city_name, $weatherData)
    {
        $result['city'] = $city_name;
        $result['date'] = date('h:i');

        $sinoptik_data = json_decode($weatherData['sinoptik_callback'], true);
        $weatherData = json_decode($weatherData['owm_callback'], true);

        $icons = $this->getProperty($weatherData['weather'][0]['id'])['icon'];
        $rand_key = array_rand($icons, 1);
        $result['weather_id'] = 200;
        $result['icon'] = $icons[$rand_key] ?: '';
        $result['temp'] = round($weatherData['main']['temp']);
        $result['desc'] = $weatherData['weather'][0]['description'];
        $result['wind_speed'] = $weatherData['wind']['speed'];
        $result['pressure'] = $weatherData['main']['pressure'];
        $result['humidity'] = $weatherData['main']['humidity'];
        $result['clouds'] = $weatherData['clouds']['all'];
        $result['detail'] = $sinoptik_data['detail'];
        $result['sunset'] = date('H:i', $weatherData['sys']['sunset']);
        $result['sunrise'] = date('H:i', $weatherData['sys']['sunrise']);

        $twig = Twig::getInstance();
        $template = $twig->load('current.twig');
        $text = include(__DIR__ . '/../../../config/texts.php');

        return $template->render([
            'text' => $text,
            'weather' => $result,
        ]);
    }

    protected function getProperty(int $weatherId): array
    {
        $weatherProperties = include(__DIR__ . '/../../../config/weather_properties.php');
        return $weatherProperties[$weatherId];
    }

}
