<?php declare(strict_types=1);

namespace App\Application\Query;

use App\Application\CachedRetriever\ItemsCachedRetrieverInterface;
use App\Application\ResponseModel\ItemNeighborsResponseModel;
use App\Application\ResponseModel\ItemResponseModel;
use PHPUnit\Framework\TestCase;

class ItemsNeighborsQueryHandlerTest extends TestCase
{
    /** @test */
    public function invoke()
    {
        $query = new ItemsNeighborsQuery();
        $query->setItemId(1);
        $query->setLanguageCode('en');

        $prevItem = (new ItemResponseModel())->setName('prev');
        $nextItem = (new ItemResponseModel())->setName('next');

        $cachedRetriever = $this->createMock(ItemsCachedRetrieverInterface::class);
        $cachedRetriever->expects($this->once())->method('getNeighborsByItem')->willReturn(new ItemNeighborsResponseModel($prevItem, $nextItem));

        $queryHandler = new ItemsNeighborsQueryHandler($cachedRetriever);
        $result = $queryHandler->__invoke($query);

        $this->assertInstanceOf(ItemNeighborsResponseModel::class, $result);
        $this->assertEquals('prev', $result->getPrev()->getName());
        $this->assertEquals('next', $result->getNext()->getName());
    }
}
