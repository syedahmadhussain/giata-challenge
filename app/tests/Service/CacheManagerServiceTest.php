<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\CacheManagerService;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Cache\CacheInterface;

class CacheManagerServiceTest extends TestCase
{
    public function testFetchCachedDataBatch(): void
    {
        $cacheMock = $this->createMock(CacheInterface::class);

        $cacheKeys = ['cache_key_1', 'cache_key_2', 'cache_key_3'];
        $expectedData = [
            'cache_key_1' => 'data_1',
            'cache_key_2' => 'data_2',
            'cache_key_3' => 'data_3',
        ];

        $cacheMock->expects($this->exactly(count($cacheKeys)))
            ->method('get')
            ->willReturnCallback(function ($key) use ($expectedData) {
                return $expectedData[$key] ?? null;
            });

        $cacheManager = new CacheManagerService($cacheMock);

        $result = $cacheManager->fetchCachedDataBatch($cacheKeys);

        $this->assertEquals($expectedData, $result);
    }
}
