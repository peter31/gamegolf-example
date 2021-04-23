<?php declare(strict_types=1);

namespace App\Infrastructure\CachedRetriever;

use App\Application\CachedRetriever\ItemOpinionsCachedRetrieverInterface;
use App\Application\Pagination\PaginationModel;
use App\Application\Repository\ItemOpinionsRepositoryInterface;
use App\Application\ResponseModel\PaginatedListResponseModel;
use JMS\Serializer\SerializerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Cache\CacheInterface;

class ItemOpinionsCachedRetrieverTest extends TestCase
{
    /** @test */
    public function getLatest()
    {
        $repository = $this->createMock(ItemOpinionsRepositoryInterface::class);

        $cacheAdapter = $this->createMock(CacheInterface::class);
        $cacheAdapter->expects($this->once())->method('get')->willReturn('{}');

        $serializer = $this->createMock(SerializerInterface::class);
        $serializer->expects($this->once())->method('deserialize')->willReturn([]);

        $cachedRetriever = new ItemOpinionsCachedRetriever($repository, $cacheAdapter, $serializer);
        $result = $cachedRetriever->getLatestWithItems('en', 4, 1, 1700);

        $this->assertInstanceOf(ItemOpinionsCachedRetrieverInterface::class, $cachedRetriever);
        $this->assertEquals([], $result->getData());
    }

    /** @test */
    public function getByItemId()
    {
        $repository = $this->createMock(ItemOpinionsRepositoryInterface::class);

        $cacheAdapter = $this->createMock(CacheInterface::class);
        $cacheAdapter->expects($this->once())->method('get')->willReturn('{}');

        $serializer = $this->createMock(SerializerInterface::class);
        $serializer->expects($this->once())->method('deserialize')->willReturn(new PaginatedListResponseModel(new PaginationModel(), []));

        $cachedRetriever = new ItemOpinionsCachedRetriever($repository, $cacheAdapter, $serializer);
        $result = $cachedRetriever->getByItemId('en', 1, 1, 10);

        $this->assertEquals([], $result->getData());
    }
}
