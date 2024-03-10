<?php

declare(strict_types=1);

namespace App\Service;

interface DataFetchingInterface {
    public function fetchHotelsUrlList(string $jsonDataUrl): array;
}