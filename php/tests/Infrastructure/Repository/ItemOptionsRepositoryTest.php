<?php declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Application\Pagination\PaginationCalculatorInterface;
use App\Application\Repository\ItemOpinionsRepositoryInterface;
use App\Application\Repository\ItemsRepositoryInterface;
use App\Application\ResponseTransformer\ItemOpinionsResponseTransformerInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class ItemOptionsRepositoryTest extends TestCase
{
    /** @test */
    public function classConstruct()
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $itemsRepository = $this->createMock(ItemsRepositoryInterface::class);
        $paginationCalculator = $this->createMock(PaginationCalculatorInterface::class);
        $transformer = $this->createMock(ItemOpinionsResponseTransformerInterface::class);

        $repository = new ItemOpinionsRepository($em, $itemsRepository, $paginationCalculator, $transformer);
        $this->assertInstanceOf(ItemOpinionsRepositoryInterface::class, $repository);
    }
}
