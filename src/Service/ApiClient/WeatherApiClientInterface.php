<?php
declare(strict_types=1);

namespace App\Service\ApiClient;

use App\Entity\ApiData;

interface WeatherApiClientInterface
{
    public function getApiData(string $city, string $country): ?ApiData;
}