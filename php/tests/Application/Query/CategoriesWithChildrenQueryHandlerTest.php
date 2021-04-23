<?php declare(strict_types=1);

namespace App\Application\Query;

use App\Application\CachedRetriever\CategoriesCachedRetrieverInterface;
use App\Application\ResponseModel\ListResponseModel;
use PHPUnit\Framework\TestCase;

class CategoriesByIdsWithChildrenQueryHandlerTest extends TestCase
{
    /** @test */
    public function invoke()
    {
        $query = $this->createMock(CategoriesWithChildrenQuery::class);
        $query->expects($this->once())->method('getSharedIds')->willReturn([1, 2, 3]);
        $query->expects($this->once())->method('getLanguageCode')->willReturn('en');
        $query->expects($this->once())->method('getMaxChildrenLevel')->willReturn(1);
        $query->expects($this->once())->method('getChildrenLimit')->willReturn(10);

        $categoriesCachedRetriever = $this->createMock(CategoriesCachedRetrieverInterface::class);
        $categoriesCachedRetriever->expects($this->once())->method('getByCategoryIdsWithChildren')->willReturn(new ListResponseModel([]));

        $queryHandler = new CategoriesWithChildrenQueryHandler($categoriesCachedRetriever);
        $result = $queryHandler->__invoke($query);

        $this->assertInstanceOf(ListResponseModel::class, $result);
        $this->assertEquals([], $result->getData());
    }
}
