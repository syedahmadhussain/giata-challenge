<?php

declare(strict_types=1);

namespace App\Service;

interface CacheManagerInterface
{
    public function fetchCachedDataBatch(array $cacheKeys): ?array;
}