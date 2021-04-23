<?php declare(strict_types=1);

namespace App\Application\Query;

use App\Application\Pagination\PaginationCalculator;
use App\Application\Pagination\PaginationModel;
use PHPUnit\Framework\TestCase;

class PaginationCalculatorTest extends TestCase
{
    /** @test */
    public function calculateCheckTotalRowsAndLimitConstant()
    {
        $paginationCalculator = new PaginationCalculator();
        $result = $paginationCalculator->calculate(100, 10, 1);

        $this->assertInstanceOf(PaginationModel::class, $result);
        $this->assertEquals(100, $result->getTotalRows());
        $this->assertEquals(10, $result->getLimit());
    }

    /**
     * @test
     * @@dataProvider calculateSuccessDataProvider
     */
    public function calculateSuccess(int $totalRows, int $limit, int $page, int $expectedOffset)
    {
        $paginationCalculator = new PaginationCalculator();
        $result = $paginationCalculator->calculate($totalRows, $limit, $page);

        $this->assertInstanceOf(PaginationModel::class, $result);
        $this->assertEquals($expectedOffset, $result->getOffset());
    }

    public static function calculateSuccessDataProvider()
    {
        return [
            [100, 10, 1, 0],
            [100, 10, 2, 10],
            [100, 10, 5, 40],
            [100, 10, 10, 90],
            [100, 3, 5, 12],
            [100, 3, 30, 87],
            [100, 3, 34, 99],

            // Return last page offset when number of page is more than the last one
            [100, 10, 11, 90],
            [100, 10, 1000000, 90],
            [100, 3, 35, 99],
        ];
    }
}
