<?php

declare(strict_types=1);

namespace App\Service;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;

class DataFetchingService implements DataFetchingInterface
{
    public const DEFAULT_TIMEOUT = 5;

    public function __construct(private readonly HttpClient $client)
    {
    }

    public function fetchHotelsUrlList(string $jsonDataUrl): array
    {
        try {
            $response = $this->client->get($jsonDataUrl, [
                'timeout' => self::DEFAULT_TIMEOUT,
            ]);

            $statusCode = $response->getStatusCode();
            if ($statusCode !== 200) {
                throw new \RuntimeException("Failed to fetch hotels: HTTP status code $statusCode");
            }

            $jsonData = json_decode($response->getBody()->getContents(), true);
            if (!isset($jsonData['urls']) || !is_array($jsonData['urls'])) {
                throw new \RuntimeException('Invalid JSON data format: "urls" field is missing or not an array');
            }

            return $jsonData['urls'];
        } catch (GuzzleException $e) {
            throw new \RuntimeException('Failed to fetch hotels: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }
}
