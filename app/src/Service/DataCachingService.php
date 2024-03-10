<?php

declare(strict_types=1);

namespace App\Service;

use Psr\Log\LoggerInterface;

class DataCachingService implements DataCachingInterface
{
    public function __construct(
        private readonly CacheManagerInterface $cacheManager,
        private readonly LoggerInterface $logger
    ) {}

    public function fetchCachedDataBatch(array $urls): array {
        $cacheKeysToUrls = array_combine(
            array_map(fn($url) => md5((string) $url), $urls),
            $urls
        );

        $results = $this->cacheManager->fetchCachedDataBatch(array_keys($cacheKeysToUrls));

        $cachedUrls = [];
        $missingUrls = [];

        foreach ($cacheKeysToUrls as $cacheKey => $url) {
            if (isset($results[$cacheKey])) {
                $cachedUrls[] = $url;
            } else {
                $missingUrls[] = $url;
            }
        }

        $missingCount = count($missingUrls);
        if ($missingCount > 0) {
            $this->logger->warning(sprintf('Not all data could be cached. Missing %d URLs.', $missingCount));
        }

        return $cachedUrls;
    }

}
