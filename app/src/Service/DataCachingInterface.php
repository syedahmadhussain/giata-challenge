<?php

declare(strict_types=1);

namespace App\Service;

interface DataCachingInterface
{
    public function fetchCachedDataBatch(array $urls): array;
}