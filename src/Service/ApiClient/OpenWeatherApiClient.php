<?php
declare(strict_types=1);

namespace App\Service\ApiClient;

use App\Entity\ApiData;

class OpenWeatherApiClient implements WeatherApiClientInterface
{
    private $baseUrl;
    private $apiKey;

    public function __construct(string $baseUrl, string $apiKey)
    {
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;
    }

    public function getApiData(string $city, string $country): ApiData
    {

    }
}
