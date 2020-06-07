<?php
declare(strict_types=1);

namespace App\Service\ApiClient;

use Symfony\Contracts\HttpClient\HttpClientInterface;

trait ApiClientBaseTrait
{
    private $baseUrl;
    private $apiKey;
    private $client;

    public function baseConfig
    (
        string $baseUrl, 
        string $apiKey, 
        HttpClientInterface $client
    ) {
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;
        $this->client = $client;
    }
}