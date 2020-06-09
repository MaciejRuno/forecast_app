<?php
declare(strict_types=1);

namespace App\Service\ApiClient;

use App\Entity\ApiData;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AccuWeatherApiClient implements WeatherApiClientInterface
{
    use ApiClientBaseTrait;

    const LOCATION_ENDPOINT = 'locations/v1/cities/search';
    const FORECAST_ENDPOINT = 'currentconditions/v1/';
    
    private $logger;

    /**
     * AccuWeatherApiClient constructor.
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
        try {
            $locationId = $this->getLocationId($city, $country);
            $data = $this->getForecast($locationId);
        } catch (\Exception $e) {
            $this->logger->error("Could not get data from AccuWeather");
            return null;
        }

        $apiData = new ApiData();
        $apiData->setTemperature($data[0]['Temperature']['Metric']['Value']);
        $apiData->setWind($data[0]['Wind']['Speed']['Metric']['Value']);
        $apiData->setHumidity($data[0]['RelativeHumidity']);
        $apiData->setRainfall($data[0]['Precip1hr']['Metric']['Value']);
        $apiData->setCreatedAt(new \DateTime());

        return $apiData;
    }

    private function getLocationId(string $city, string $country): string
    {
        $queryString = sprintf(
            "%s,%s",
            strtolower($city),
            strtolower($country)
        );

        $response = $this->client->request(
            'GET',
            $this->baseUrl . $this::LOCATION_ENDPOINT,
            [
                'query' => [
                    'q' => $queryString,
                    'apikey' => $this->apiKey,
                ],
            ],
        );

        return $response->toArray()[0]['Key'];
    }

    private function getForecast(string $locationId): array
    {
        $response = $this->client->request(
            'GET',
            $this->baseUrl . $this::FORECAST_ENDPOINT. $locationId,
            [
                'query' => [
                    'apikey' => $this->apiKey,
                    'details' => 'true',
                ],
            ],
        );

        return $response->toArray();
    }
}
