<?php

declare(strict_types=1);

namespace App\Service;

interface DataFilterInterface {
    public function filterHotels(array $hotels): array;
}