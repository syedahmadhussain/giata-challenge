<?php

declare(strict_types=1);

namespace App\Service;

class DataProcessingService implements DataProcessingInterface
{
    public function __construct(
        private readonly CacheManagerInterface $cacheManager,
        private readonly DataFilterInterface $filterService
    )
    {
    }

    public function processCachedData(array $cachedUrls): array
    {
        $cacheKeys = array_map(fn($url) => md5((string) $url), $cachedUrls);
        $hotelsData = $this->cacheManager->fetchCachedDataBatch($cacheKeys);

        $hotels = array_filter($hotelsData, fn($hotel) => $hotel !== null);

        return $this->filterService->filterHotels($hotels);
    }
}
