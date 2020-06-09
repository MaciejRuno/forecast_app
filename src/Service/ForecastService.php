<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\ApiData;
use App\Entity\RequestData;
use App\Service\ApiClient\WeatherApiClientInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ForecastService 
{
    private $em;
    private $cache;

    private $weatherApis = [];

    public function __construct
    (
        EntityManagerInterface $em,
        CacheInterface $cache
    ) {
        $this->em = $em;
        $this->cache = $cache;
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
        $requestData->setCity($city);
        $requestData->setCountry($country);

        foreach ($this->weatherApis as $api) {
            $cacheKey = sprintf(
                "%s.%s.%s", 
                (new \ReflectionClass($api))->getShortName(), 
                $city, 
                $country
            );

            /** @var ApiData $apiData */
            $apiData = $this->cache->get(
                $cacheKey, 
                function (ItemInterface $item) use ($api, $city, $country) {
                    $item->expiresAfter(60 * 5); // 5 min cache
                
                    return $api->getApiData($city, $country);
                }
            );

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
     * @throws \Exception
     */
    public function getForecastFromRequestData(RequestData $requestData): array
    {
        if (2 > count($requestData->getApiData())) {
            throw new \Exception("Less than 2 apis returned a valid response");
        }

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