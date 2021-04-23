<?php declare(strict_types=1);

namespace App\Infrastructure\CachedRetriever;

use App\Application\CachedRetriever\ItemsCachedRetrieverInterface;
use App\Application\Repository\ItemsRepositoryInterface;
use App\Application\ResponseModel\ItemResponseModel;
use JMS\Serializer\SerializerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Cache\CacheInterface;

class ItemsCachedRetrieverTest extends TestCase
{
    /** @var ItemsRepositoryInterface|MockObject */
    private $repository;

    /** @var CacheInterface|MockObject */
    private $cacheAdapter;

    /** @var SerializerInterface|MockObject */
    private $serializer;

    public function setUp(): void
    {
        $this->repository = $this->createMock(ItemsRepositoryInterface::class);
        $this->cacheAdapter = $this->createMock(CacheInterface::class);
        $this->serializer = $this->createMock(SerializerInterface::class);
    }

    public function contruct()
    {
        $cachedRetriever = new ItemsCachedRetriever($this->repository, $this->cacheAdapter, $this->serializer);
        $this->assertInstanceOf(ItemsCachedRetrieverInterface::class, $cachedRetriever);
    }

    /** @test */
    public function getPublicByIdSuccess()
    {
        $this->cacheAdapter->expects($this->once())->method('get')->willReturn('{}');
        $this->serializer->expects($this->once())->method('deserialize')->willReturn(new ItemResponseModel());

        $cachedRetriever = new ItemsCachedRetriever($this->repository, $this->cacheAdapter, $this->serializer);
        $result = $cachedRetriever->getPublicById('en', 1);

        $this->assertInstanceOf(ItemResponseModel::class, $result);
    }

    /** @test */
    public function getPublicByIdNotFOund()
    {
        $this->cacheAdapter->expects($this->once())->method('get')->willReturn('null');

        $cachedRetriever = new ItemsCachedRetriever($this->repository, $this->cacheAdapter, $this->serializer);
        $result = $cachedRetriever->getPublicById('en', 1);

        $this->assertNull($result);
    }
}
