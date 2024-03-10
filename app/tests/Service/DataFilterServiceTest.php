<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\DataFilterService;
use PHPUnit\Framework\TestCase;

class DataFilterServiceTest extends TestCase
{
    public function testFilterHotels()
    {
        $dataFilterService = new DataFilterService();

        $hotels = [
            [
                'giataId' => 1004,
                'ratings' => [
                    ['value' => 2],
                ],
            ],
            [
                'giataId' => 1001,
                'ratings' => [
                    ['value' => 3],
                ],
            ]
        ];

        $expectedResult = [
            [
                'giataId' => 1004,
                'ratings' => [
                    ['value' => 2],
                ],
            ],
        ];

        $result = $dataFilterService->filterHotels($hotels);

        $this->assertEquals($expectedResult, $result);
    }
}
