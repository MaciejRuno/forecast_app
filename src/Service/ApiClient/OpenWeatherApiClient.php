<?php
declare(strict_types=1);

namespace App\Service\ApiClient;

use App\Entity\ApiData;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OpenWeatherApiClient implements WeatherApiClientInterface
{
    use ApiClientBaseTrait;

    const FORECAST_ENDPOINT = 'data/2.5/weather';

    /**
     * OpenWeatherApiClient constructor.
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
        $queryString = sprintf(
            "%s,%s",
            strtolower($city),
            strtolower($country)
        );

        $response = $this->client->request(
            'GET',
            $this->baseUrl . $this::FORECAST_ENDPOINT,
            [
                'query' => [
                    'q' => $queryString,
                    'appid' => $this->apiKey,
                    'units' => 'metric',
                ],
            ],
        );

        $data = $response->toArray();

        $apiData = new ApiData();
        $apiData->setTemperature($data['main']['temp']);
        $apiData->setWind($data['wind']['speed']);
        $apiData->setHumidity($data['main']['humidity']);
        $apiData->setRainfall($data['rain']['1h'] ?? 0);
        $apiData->setCreatedAt(new \DateTime());

        return $apiData;
    }
}
