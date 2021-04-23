<?php declare(strict_types=1);

namespace App\Application\Pagination;

interface PaginationCalculatorInterface
{
	public function calculate(int $totalRows, int $rowsPerPage, int $pageNum, int $boxes = 10): PaginationModel;
}
