<?php declare(strict_types=1);

namespace App\Application\Query;

use App\Application\CachedRetriever\ItemOpinionsCachedRetrieverInterface;
use App\Application\Pagination\PaginationModel;
use App\Application\ResponseModel\PaginatedListResponseModel;
use PHPUnit\Framework\TestCase;

class ItemOpinionsByItemQueryHandlerTest extends TestCase
{
    /** @test */
    public function invoke()
    {
        $query = new ItemOpinionsByItemQuery();
        $query->setLanguageCode('en');
        $query->setPage(1);
        $query->setLimit(3);
        $query->setItemId(1);

        $cachedRetriever = $this->createMock(ItemOpinionsCachedRetrieverInterface::class);
        $cachedRetriever->expects($this->once())->method('getByItemId')->willReturn(new PaginatedListResponseModel(new PaginationModel(), []));

        $queryHandler = new ItemOpinionsByItemQueryHandler($cachedRetriever);
        $result = $queryHandler->__invoke($query);

        $this->assertInstanceOf(PaginatedListResponseModel::class, $result);
        $this->assertEquals([], $result->getData());
    }
}
