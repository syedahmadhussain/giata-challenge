<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\DispatchDataService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\TransportException;
use Symfony\Component\Messenger\MessageBusInterface;

class DispatchDataServiceTest extends TestCase
{
    public function testDispatchData()
    {
        $messageBusMock = $this->getMockBuilder(MessageBusInterface::class)
            ->getMock();

        $dispatchDataService = new DispatchDataService($messageBusMock);

        $urls = [
            'https://hotel1.com',
            'https://hotel2.com',
            'https://hotel3.com',
        ];

        $messageBusMock->expects($this->exactly(count($urls)))
            ->method('dispatch')
            ->willReturn(new Envelope(new \stdClass()));

        $dispatchDataService->dispatchData($urls);
    }

    public function testDispatchDataWithTransportException()
    {
        $messageBusMock = $this->getMockBuilder(MessageBusInterface::class)
            ->getMock();

        $dispatchDataService = new DispatchDataService($messageBusMock);

        $urls = ['https://hotel1.com'];

        $messageBusMock->expects($this->once())
            ->method('dispatch')
            ->willThrowException(new TransportException('Failed to dispatch message'));

        $this->expectException(\RuntimeException::class);

        $dispatchDataService->dispatchData($urls);
    }

    public function testDispatchDataWithThrowable()
    {
        $messageBusMock = $this->getMockBuilder(MessageBusInterface::class)
            ->getMock();

        $dispatchDataService = new DispatchDataService($messageBusMock);

        $urls = ['https://hotel1.com'];

        $messageBusMock->expects($this->once())
            ->method('dispatch')
            ->willThrowException(new \Exception('Unexpected error'));

        $this->expectException(\RuntimeException::class);

        $dispatchDataService->dispatchData($urls);
    }
}
