<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\ApiData;
use App\Entity\RequestData;
use App\Service\ApiClient\WeatherApiClientInterface;
use Doctrine\ORM\EntityManagerInterface;

class ForecastService 
{
    private $em;
    private $requestRepository;

    private $weatherApis = [];

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param WeatherApiClientInterface $api
     */
    public function addWeatherApi(WeatherApiClientInterface $api)
    {
        $this->weatherApis[] = $api;
    }

    /**
     * @param string $city
     * @param string $country
     * @param string $clientIp
     * @return RequestData|null
     */
    public function getRequestData
    (
        string $city, 
        string $country, 
        string $clientIp
    ): ?RequestData {
        $requestData = new RequestData();
        $requestData->setUserIp(\ip2long($clientIp));
        $requestData->setCreatedAt(new \DateTime());

        foreach ($this->weatherApis as $api) {
            /** @var ApiData $apiData */
            $apiData = $api->getApiData($city, $country);

            if ($apiData) {
                $requestData->addApiData($apiData);
                $this->em->persist($apiData);
            }
        }
        $this->em->flush();
        $this->em->clear();

        return $requestData;
    }

    /**
     * @param RequestData $requestData
     * @return array
     */
    public function getForecastFromRequestData(RequestData $requestData): array
    {
        $allForecasts = [
            'temperature' => [],
            'wind' => [],
            'humidity' => [],
            'rainfall' => [],
        ];

        /** @var ApiData $apiData */
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