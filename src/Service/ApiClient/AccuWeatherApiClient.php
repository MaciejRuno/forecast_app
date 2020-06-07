<?php
declare(strict_types=1);

namespace App\Service\ApiClient;

use App\Entity\ApiData;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AccuWeatherApiClient implements WeatherApiClientInterface
{
    use ApiClientBaseTrait;

    public function __construct
    (
        string $baseUrl, 
        string $apiKey, 
        HttpClientInterface $client
    ) {
        $this->baseConfig($baseUrl, $apiKey, $client);
    }

    public function getApiData(string $city, string $country): ApiData
    {
    }
}
