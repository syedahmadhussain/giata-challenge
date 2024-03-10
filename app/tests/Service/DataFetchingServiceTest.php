<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\DataFetchingService;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class DataFetchingServiceTest extends TestCase
{
    public function testFetchHotelsUrlList()
    {
        $httpClientMock = $this->getMockBuilder(HttpClient::class)
            ->getMock();

        $dataFetchingService = new DataFetchingService($httpClientMock);

        $jsonDataUrl = 'https://example.com/data.json';

        $sampleJsonData = [
            'urls' => [
                'https://hotel1.com',
                'https://hotel2.com',
                'https://hotel3.com',
            ]
        ];

        $responseMock = new Response(200, [], json_encode($sampleJsonData));
        $httpClientMock->expects($this->once())
            ->method('get')
            ->with($jsonDataUrl, ['timeout' => DataFetchingService::DEFAULT_TIMEOUT])
            ->willReturn($responseMock);

        $result = $dataFetchingService->fetchHotelsUrlList($jsonDataUrl);

        $this->assertEquals($sampleJsonData['urls'], $result);
    }

    public function testFetchHotelsUrlListWithInvalidJson()
    {
        $httpClientMock = $this->getMockBuilder(HttpClient::class)
            ->getMock();

        $dataFetchingService = new DataFetchingService($httpClientMock);

        $jsonDataUrl = 'https://example.com/data.json';

        $sampleJsonData = [];

        $responseMock = new Response(200, [], json_encode($sampleJsonData));
        $httpClientMock->expects($this->once())
            ->method('get')
            ->with($jsonDataUrl, ['timeout' => DataFetchingService::DEFAULT_TIMEOUT])
            ->willReturn($responseMock);

        $this->expectException(\RuntimeException::class);

        $dataFetchingService->fetchHotelsUrlList($jsonDataUrl);
    }

    public function testFetchHotelsUrlListWithInvalidHttpResponse()
    {
        $httpClientMock = $this->getMockBuilder(HttpClient::class)
            ->getMock();

        $dataFetchingService = new DataFetchingService($httpClientMock);

        $jsonDataUrl = 'https://example.com/data.json';

        $responseMock = new Response(404);
        $httpClientMock->expects($this->once())
            ->method('get')
            ->with($jsonDataUrl, ['timeout' => DataFetchingService::DEFAULT_TIMEOUT])
            ->willReturn($responseMock);

        $this->expectException(\RuntimeException::class);

        $dataFetchingService->fetchHotelsUrlList($jsonDataUrl);
    }

    public function testFetchHotelsUrlListWithGuzzleException()
    {
        $httpClientMock = $this->getMockBuilder(HttpClient::class)
            ->getMock();

        $dataFetchingService = new DataFetchingService($httpClientMock);

        $jsonDataUrl = 'https://example.com/data.json';

        $httpClientMock->expects($this->once())
            ->method('get')
            ->with($jsonDataUrl, ['timeout' => DataFetchingService::DEFAULT_TIMEOUT])
            ->willThrowException(new RequestException('Request failed', new \GuzzleHttp\Psr7\Request('GET', $jsonDataUrl)));

        $this->expectException(\RuntimeException::class);

        $dataFetchingService->fetchHotelsUrlList($jsonDataUrl);
    }
}
