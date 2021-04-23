<?php declare(strict_types=1);

namespace App\Infrastructure\CachedRetriever;

use App\Application\CachedRetriever\CollectionsCachedRetrieverInterface;
use App\Application\Repository\CollectionsRepositoryInterface;
use JMS\Serializer\SerializerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Cache\CacheInterface;

class CollectionsCachedRetrieverTest extends TestCase
{
    /** @test */
    public function getLatestWithItems()
    {
        $collectionsRepository = $this->createMock(CollectionsRepositoryInterface::class);

        $cacheAdapter = $this->createMock(CacheInterface::class);
        $cacheAdapter->expects($this->once())->method('get')->willReturn('{}');

        $serializer = $this->createMock(SerializerInterface::class);
        $serializer->expects($this->once())->method('deserialize')->willReturn([]);

        $cachedRetriever = new CollectionsCachedRetriever($collectionsRepository, $cacheAdapter, $serializer);
        $result = $cachedRetriever->getLatestWithItems('en', 5, 3);

        $this->assertInstanceOf(CollectionsCachedRetrieverInterface::class, $cachedRetriever);
        $this->assertEquals([], $result->getData());
    }
}
