<?php

declare(strict_types=1);

namespace App\Service;

class DataFilterService implements DataFilterInterface
{
    public function filterHotels(array $hotels): array
    {
        return array_filter($hotels, function ($hotel) {
            if (!isset($hotel['giataId']) || empty($hotel['ratings'])) {
                return false;
            }

            $giataId = $hotel['giataId'];
            foreach ($hotel['ratings'] as $rating) {
                if (!isset($rating['value'])) {
                    continue;
                }

                $ratingValue = filter_var($rating['value'], FILTER_VALIDATE_INT, [
                    'options' => ['min_range' => 2, 'max_range' => 7]
                ]);

                if ($ratingValue !== false && $giataId % $ratingValue === 0) {
                    return true;
                }
            }
            return false;
        });
    }
}
