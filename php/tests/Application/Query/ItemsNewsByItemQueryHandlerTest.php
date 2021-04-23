<?php declare(strict_types=1);

namespace App\Application\Query;

use App\Application\CachedRetriever\NewsCachedRetrieverInterface;
use App\Application\ResponseModel\ListResponseModel;
use PHPUnit\Framework\TestCase;

class ItemsNewsByItemQueryHandlerTest extends TestCase
{
    /** @test */
    public function invoke()
    {
        $query = new ItemsNewsByItemQuery();
        $query->setLanguageCode('en');
        $query->setItemId(100);
        $query->setLimit(5);

        $cachedRetriever = $this->createMock(NewsCachedRetrieverInterface::class);
        $cachedRetriever->expects($this->once())->method('getByItem')->willReturn(new ListResponseModel([]));

        $queryHandler = new ItemsNewsByItemQueryHandler($cachedRetriever);
        $result = $queryHandler->__invoke($query);

        $this->assertInstanceOf(ListResponseModel::class, $result);
    }
}
