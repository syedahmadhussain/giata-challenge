<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\DataProcessingService;
use App\Service\CacheManagerInterface;
use App\Service\DataFilterInterface;
use PHPUnit\Framework\TestCase;

final class DataProcessingServiceTest extends TestCase
{
    public function testProcessCachedData()
    {
        $cacheManagerMock = $this->getMockBuilder(CacheManagerInterface::class)
            ->getMock();
        $filterServiceMock = $this->getMockBuilder(DataFilterInterface::class)
            ->getMock();

        $cacheKeys = ['cache_key_1', 'cache_key_3'];
        $hashedCacheKeys = array_map('md5', $cacheKeys);
        $cachedData = [
            'cache_key_1' => 'hotel_1_data',
            'cache_key_3' => 'hotel_3_data',
        ];

        $cacheManagerMock->expects($this->once())
            ->method('fetchCachedDataBatch')
            ->with($hashedCacheKeys)
            ->willReturn($cachedData);

        $filteredHotels = ['hotel_1_data', 'hotel_3_data'];
        $filterServiceMock->expects($this->once())
            ->method('filterHotels')
            ->with($cachedData)
            ->willReturn($filteredHotels);

        $dataProcessingService = new DataProcessingService($cacheManagerMock, $filterServiceMock);

        $filterServiceMock->expects($this->once())
            ->method('filterHotels')
            ->with($cachedData)
            ->willReturn($filteredHotels);

        $result = $dataProcessingService->processCachedData($cacheKeys);

        $this->assertEquals($filteredHotels, $result);
    }
}
