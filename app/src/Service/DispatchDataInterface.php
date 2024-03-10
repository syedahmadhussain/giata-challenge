<?php

declare(strict_types=1);

namespace App\Service;

interface DispatchDataInterface
{
    public function dispatchData(array $urls): void;
}