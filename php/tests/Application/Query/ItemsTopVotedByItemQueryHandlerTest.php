<?php declare(strict_types=1);

namespace App\Application\Query;

use App\Application\CachedRetriever\ItemsCachedRetrieverInterface;
use App\Application\ResponseModel\ListResponseModel;
use PHPUnit\Framework\TestCase;

class ItemsTopVotedByItemQueryHandlerTest extends TestCase
{
    /** @test */
    public function invoke()
    {
        $query = new ItemsTopVotedByItemQuery();
        $query->setLanguageCode('en');
        $query->setItemId(100);
        $query->setItemId(5);

        $cachedRetriever = $this->createMock(ItemsCachedRetrieverInterface::class);
        $cachedRetriever->expects($this->once())->method('getTopVotedByItem')->willReturn(new ListResponseModel([]));

        $queryHandler = new ItemsTopVotedByItemQueryHandler($cachedRetriever);
        $result = $queryHandler->__invoke($query);

        $this->assertInstanceOf(ListResponseModel::class, $result);
    }
}
