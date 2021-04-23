<?php declare(strict_types=1);

namespace App\Application\Query;

use App\Application\CachedRetriever\ItemsCachedRetrieverInterface;
use App\Application\ResponseModel\ListResponseModel;
use PHPUnit\Framework\TestCase;

class ItemsRelatedByItemQueryHandlerTest extends TestCase
{
    /** @test */
    public function invoke()
    {
        $query = new ItemsRelatedByItemQuery();
        $query->setLanguageCode('en');
        $query->setItemId(100);
        $query->setLimit(5);

        $cachedRetriever = $this->createMock(ItemsCachedRetrieverInterface::class);
        $cachedRetriever->expects($this->once())->method('getRelatedByItem')->willReturn(new ListResponseModel([]));

        $queryHandler = new ItemsRelatedByItemQueryHandler($cachedRetriever);
        $result = $queryHandler->__invoke($query);

        $this->assertInstanceOf(ListResponseModel::class, $result);
    }
}
