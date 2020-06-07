<?php
declare(strict_types=1);

namespace App\Service\ApiClient;

use App\Entity\ApiData;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OpenWeatherApiClient implements WeatherApiClientInterface
{
    private $baseUrl;
    private $apiKey;
    private $client;

    public function __construct(string $baseUrl, string $apiKey, HttpClientInterface $client)
    {
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;
        $this->client = $client;
    }

    public function getApiData(string $city, string $country): ApiData
    {

    }
}
