<?php

declare(strict_types=1);

namespace App\Service;

interface DataProcessingInterface
{
    public function processCachedData(array $cachedUrls): array;
}