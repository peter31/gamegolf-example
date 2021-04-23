<?php declare(strict_types=1);

namespace App\Application\Pagination;

class PaginationCalculator implements PaginationCalculatorInterface
{
	public function calculate(int $totalRows, int $limit, int $pageNumber, int $boxes = 10): PaginationModel
    {
        $this->validateParameters($totalRows, $limit, $pageNumber, $boxes);

        $result = new PaginationModel();

        $lastPage = (int) ceil($totalRows / $limit);

        if ($pageNumber < 1) {
            $pageNumber = 1;
        } elseif ($pageNumber > $lastPage) {
            $pageNumber = $lastPage;
        }

        $upto = ($pageNumber - 1) * $limit;

        $result->setLimit($limit);
        $result->setOffset($upto);
        $result->setTotalRows($totalRows);
        $result->setCurrent($pageNumber);

        if ($pageNumber === 1) {
            $result->setPrevious($pageNumber);
        } else {
            $result->setPrevious($pageNumber - 1);
        }

        if ($pageNumber == $lastPage) {
            $result->setNext($lastPage);
        } else {
            $result->setNext($pageNumber + 1);
        }
        $result->setLast($lastPage);
        $result->setPages(
            $this->getSurroundingPages($pageNumber, $lastPage, $result->getNext(), $boxes)
        );

		return $result;
	}

	private function getSurroundingPages(int $pageNumber, int $lastPage, int $next, int $boxes): array
	{
        $result = [];
		if ($pageNumber == 1) {
			if ($next == $pageNumber) {
                return [1];
            }

			for ($i = 0; $i < $boxes; $i++) {
				if ($i == $lastPage) {
                    break;
                }

				array_push($result, $i + 1);
			}

			return $result;
		}

		if ($pageNumber == $lastPage) {
			$start = $lastPage - $boxes;
			if ($start < 1) {
                $start = 0;
            }

			for ($i = $start; $i < $lastPage; $i++) {
				array_push($result, $i + 1);
			}

			return $result;
		}

		$start = $pageNumber - $boxes;
		if ($start < 1) {
            $start = 0;
        }

		for ($i = $start; $i < $pageNumber; $i++) {
			array_push($result, $i + 1);
		}

		for ($i = ($pageNumber + 1); $i < ($pageNumber + $boxes); $i++) {
			if ($i == ($lastPage + 1)) {
                break;
            }

			array_push($result, $i);
		}

		return $result;
	}

	private function validateParameters(int $totalRows, int $limit, int $pageNumber, int $boxes)
    {
        if ($totalRows < 0) {
            throw new \InvalidArgumentException(sprintf('[Pagination] Total rows should be more than or equals to 0'));
        }

        if ($limit < 1) {
            throw new \InvalidArgumentException(sprintf('[Pagination] Limit should be more than or equals to 1'));
        }

        if ($pageNumber < 1) {
            throw new \InvalidArgumentException(sprintf('[Pagination] Page number should be more than or equals to 1'));
        }

        if ($boxes < 1) {
            throw new \InvalidArgumentException(sprintf('[Pagination] Boxes should be more than or equals to 1'));
        }
    }
}
