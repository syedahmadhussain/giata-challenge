<?php

declare(strict_types=1);

namespace App\Service;

use App\Message\FetchHotelDataMessage;
use Symfony\Component\Messenger\Exception\TransportException;
use Symfony\Component\Messenger\MessageBusInterface;

class DispatchDataService implements DispatchDataInterface
{
    public function __construct(private readonly MessageBusInterface $messageBus)
    {
    }

    public function dispatchData(array $urls): void
    {
        try {
            foreach ($urls as $url) {
                $this->messageBus->dispatch(new FetchHotelDataMessage($url));
            }
        } catch (TransportException $e) {
            throw new \RuntimeException('Failed to dispatch message: ' . $e->getMessage(), $e->getCode(), $e);
        } catch (\Throwable $e) {
            throw new \RuntimeException('An unexpected error occurred: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }
}