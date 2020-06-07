<?php
declare(strict_types=1);

namespace App\Service;

use App\Service\ApiClient\WeatherApiClientInterface;

class ForecastService 
{
    private $weatherApis = [];

    public function addWeatherApi(WeatherApiClientInterface $api)
    {
        $this->weatherApis[] = $api;
    }

    public function getApis()
    {
        return $this->weatherApis;
    }
}