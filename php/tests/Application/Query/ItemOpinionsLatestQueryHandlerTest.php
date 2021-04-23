<?php declare(strict_types=1);

namespace App\Application\Query;

use App\Application\CachedRetriever\ItemOpinionsCachedRetrieverInterface;
use App\Application\ResponseModel\ListResponseModel;
use PHPUnit\Framework\TestCase;

class ItemOpinionsLatestQueryHandlerTest extends TestCase
{
    /** @test */
    public function invoke()
    {
        $query = new ItemOpinionsLatestQuery();
        $query->setLanguageCode('en');
        $query->setCategoryId(100);
        $query->setTypeId(1);
        $query->setLimit(4);

        $itemOpinionsCachedRetriever = $this->createMock(ItemOpinionsCachedRetrieverInterface::class);
        $itemOpinionsCachedRetriever->expects($this->once())->method('getLatestWithItems')->willReturn(new ListResponseModel([]));

        $queryHandler = new ItemOpinionsLatestQueryHandler($itemOpinionsCachedRetriever);
        $result = $queryHandler->__invoke($query);

        $this->assertInstanceOf(ListResponseModel::class, $result);
        $this->assertEquals([], $result->getData());
    }
}
