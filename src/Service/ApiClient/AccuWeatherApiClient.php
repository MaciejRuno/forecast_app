<?php
declare(strict_types=1);

namespace App\Service\ApiClient;

use App\Entity\ApiData;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AccuWeatherApiClient implements WeatherApiClientInterface
{
    use ApiClientBaseTrait;

    /**
     * AccuWeatherApiClient constructor.
     * @param string $baseUrl
     * @param string $apiKey
     * @param HttpClientInterface $client
     */
    public function __construct
    (
        string $baseUrl, 
        string $apiKey, 
        HttpClientInterface $client
    ) {
        $this->baseConfig($baseUrl, $apiKey, $client);
    }

    /**
     * @param string $city
     * @param string $country
     * @return ApiData|null
     */
    public function getApiData(string $city, string $country): ?ApiData
    {
        return null;
    }
}
