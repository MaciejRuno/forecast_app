<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\RequestData;
use App\Service\ApiClient\WeatherApiClientInterface;

class ForecastService 
{
    private $weatherApis = [];

    public function addWeatherApi(WeatherApiClientInterface $api)
    {
        $this->weatherApis[] = $api;
    }

    public function getRequestData
    (
        string $city, 
        string $country, 
        string $clientIp
    ): ?RequestData {
        $apiData = [];

        $requestData = new RequestData();
        $requestData->setUserIp(\ip2long($clientIp));
        $requestData->setCreatedAt(new \DateTime());


        foreach ($this->weatherApis as $api) {
            $apiDatum = $api->getApiData($city, $country);

            if ($apiDatum) {
                $requestData->addApiData($apiDatum);
                $apiData[] = $apiDatum;
            }
        }

        return $requestData;
    }

    public function getForecastFromRequestData(RequestData $requestData): array
    {
        $allForecasts = [
            'temperature' => [],
            'wind' => [],
            'humidity' => [],
            'rainfall' => [],
        ];

        foreach ($requestData->getApiData() as $apiData) {
            $allForecasts['temperature'][] = $apiData->getTemperature();
            $allForecasts['wind'][] = $apiData->getWind();
            $allForecasts['humidity'][] = $apiData->getHumidity();
            $allForecasts['rainfall'][] = $apiData->getRainfall();
        }

        $averageForecast = [];

        foreach ($allForecasts as $key => $values) {
            $averageForecast[$key] = \array_sum($values) / \count($values);
        }

        return $averageForecast;
    }
}