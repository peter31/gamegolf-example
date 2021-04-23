<?php declare(strict_types=1);

namespace App\Application\Query;

use App\Application\CachedRetriever\ItemsCachedRetrieverInterface;
use App\Application\ResponseModel\ItemResponseModel;
use PHPUnit\Framework\TestCase;

class ItemsPublicByIdQueryHandlerTest extends TestCase
{
    /** @test */
    public function invoke()
    {
        $query = new ItemsPublicByIdQuery();
        $query->setLanguageCode('en');
        $query->setId(3);

        $itemModel = new ItemResponseModel();
        $itemModel->setId(1);

        $cachedRetriever = $this->createMock(ItemsCachedRetrieverInterface::class);
        $cachedRetriever->expects($this->once())->method('getPublicById')->willReturn($itemModel);

        $queryHandler = new ItemsPublicByIdQueryHandler($cachedRetriever);
        $result = $queryHandler->__invoke($query);

        $this->assertInstanceOf(ItemResponseModel::class, $result);
        $this->assertEquals(1, $result->getId());
    }
}
