<?php

declare(strict_types=1);

namespace App\Tests\MessageHandler;

use App\MessageHandler\FetchHotelDataMessageHandler;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class FetchHotelDataMessageHandlerTest extends TestCase
{
    private FetchHotelDataMessageHandler $handler;
    private HttpClientInterface $httpClientMock;
    private LoggerInterface $loggerMock;

    protected function setUp(): void
    {
        $this->httpClientMock = $this->createMock(HttpClientInterface::class);
        $cacheMock = $this->createMock(CacheInterface::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);

        $this->handler = new FetchHotelDataMessageHandler(
            $this->httpClientMock,
            $cacheMock,
            $this->loggerMock
        );
    }

    public function testFetchDataSuccess()
    {
        $url = 'http://example.com/hotel';
        $responseBody = json_encode(['giataId' => 123, 'ratings' => [/* Ratings data */]]);

        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->expects($this->once())
            ->method('getContent')
            ->willReturn($responseBody);

        $responseMock->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(200);

        $this->httpClientMock->expects($this->once())
            ->method('request')
            ->with('GET', $url)
            ->willReturn($responseMock);


        $itemMock = $this->createMock(ItemInterface::class);
        $itemMock->expects($this->once())
            ->method('expiresAfter')
            ->with(3600);

        $data = $this->handler->fetchData($itemMock, $url);
        $this->assertEquals(['giataId' => 123, 'ratings' => [/* Ratings data */]], $data);
    }

    public function testFetchDataFailure()
    {
        $url = 'http://example.com/hotel';
        $this->httpClientMock->expects($this->once())
            ->method('request')
            ->with('GET', $url)
            ->willReturn(new MockResponse('', ['http_code' => 404]));

        $this->loggerMock->expects($this->once())
            ->method('error')
            ->with('Failed to fetch hotel data');

        $data = $this->handler->fetchData($this->createMock(ItemInterface::class), $url);
        $this->assertNull($data);
    }

    public function testFetchDataException()
    {
        $url = 'http://example.com/hotel';
        $this->httpClientMock->expects($this->once())
            ->method('request')
            ->with('GET', $url)
            ->willThrowException(new \Exception('Failed to fetch data'));

        $this->loggerMock->expects($this->once())
            ->method('error')
            ->with('Failed to fetch hotel data');

        $data = $this->handler->fetchData($this->createMock(ItemInterface::class), $url);
        $this->assertNull($data);
    }
}
