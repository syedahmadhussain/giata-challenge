<?php

declare(strict_types=1);

namespace App\Message;

class FetchHotelDataMessage
{
    public function __construct(private readonly string $url)
    {
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}