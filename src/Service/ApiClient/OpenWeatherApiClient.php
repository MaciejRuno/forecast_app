<?php
declare(strict_types=1);

namespace App\Service\ApiClient;

use App\Entity\ApiData;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OpenWeatherApiClient implements WeatherApiClientInterface
{
    use ApiClientBaseTrait;

    const FORECAST_ENDPOINT = 'data/2.5/weather';

    private $logger;

    /**
     * OpenWeatherApiClient constructor.
     * @param string $baseUrl
     * @param string $apiKey
     * @param HttpClientInterface $client
     * @param LoggerInterface $logger
     */
    public function __construct
    (
        string $baseUrl, 
        string $apiKey, 
        HttpClientInterface $client,
        LoggerInterface $logger
    ) {
        $this->baseConfig($baseUrl, $apiKey, $client);
        $this->logger = $logger;
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


        try {
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
        } catch (\Exception $e) {
            $this->logger->error("Could not get data from OpenWeather");
            return null;
        }

        $apiData = new ApiData();
        $apiData->setTemperature($data['main']['temp']);
        $apiData->setWind($data['wind']['speed']);
        $apiData->setHumidity($data['main']['humidity']);
        $apiData->setRainfall($data['rain']['1h'] ?? 0);
        $apiData->setCreatedAt(new \DateTime());

        return $apiData;
    }
}
