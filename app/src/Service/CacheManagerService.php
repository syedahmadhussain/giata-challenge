<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Contracts\Cache\CacheInterface;

class CacheManagerService implements CacheManagerInterface
{
    public function __construct(private readonly CacheInterface $cache) {}

    public function fetchCachedDataBatch(array $cacheKeys): array {
        $results = [];
        foreach ($cacheKeys as $cacheKey) {
            $results[$cacheKey] = $this->cache->get($cacheKey, function() { return null; });
        }
        return $results;
    }
}
