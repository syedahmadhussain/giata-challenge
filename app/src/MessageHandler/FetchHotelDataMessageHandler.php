<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\FetchHotelDataMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsMessageHandler]
class FetchHotelDataMessageHandler
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly CacheInterface      $cache,
        private readonly LoggerInterface     $logger
    )
    {
    }

    public function fetchData(ItemInterface $item, string $url): ?array
    {
        try {
            $response = $this->client->request('GET', $url);
            $content = $response->getContent();
            if ($response->getStatusCode() === 200) {
                $data = json_decode($content, true);
                if (isset($data['giataId'], $data['ratings'])) {
                    $item->expiresAfter(3600);
                    return [
                        'giataId' => $data['giataId'],
                        'ratings' => $data['ratings'],
                    ];
                } else {
                    $this->logger->error('Required fields missing in the API response');
                }
            } else {
                $this->logger->error("API request failed with status code: {$response->getStatusCode()}");
            }
        } catch (\Throwable $e) {
            $this->logger->error("Failed to fetch hotel data");
        }
        return null;
    }

    public function __invoke(FetchHotelDataMessage $message): void
    {
        $url = $message->getUrl();
        $cacheKey = md5($url);
        $this->cache->get($cacheKey, function (ItemInterface $item) use ($url) {
            $data = $this->fetchData($item, $url);
            $item->set($data);
            return $data;
        });
    }
}
