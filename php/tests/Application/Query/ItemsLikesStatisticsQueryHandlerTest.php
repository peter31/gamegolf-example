<?php declare(strict_types=1);

namespace App\Application\Query;

use App\Application\CachedRetriever\ItemsCachedRetrieverInterface;
use App\Application\ResponseModel\ItemLikesStatisticsResponseModel;
use PHPUnit\Framework\TestCase;

class ItemsLikesStatisticsQueryHandlerTest extends TestCase
{
    /** @test */
    public function invoke()
    {
        $query = new ItemsLikesStatisticsQuery();
        $query->setLanguageCode('en');
        $query->setItemId(100);

        $cachedRetriever = $this->createMock(ItemsCachedRetrieverInterface::class);
        $cachedRetriever->expects($this->once())->method('getLikesStatistics')->willReturn(new ItemLikesStatisticsResponseModel());

        $queryHandler = new ItemsLikesStatisticsQueryHandler($cachedRetriever);
        $result = $queryHandler->__invoke($query);

        $this->assertInstanceOf(ItemLikesStatisticsResponseModel::class, $result);
    }
}
