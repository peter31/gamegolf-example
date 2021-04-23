<?php declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Application\Pagination\PaginationCalculatorInterface;
use App\Application\Repository\ItemsRepositoryInterface;
use App\Application\ResponseTransformer\ItemApksResponseTransformerInterface;
use App\Application\ResponseTransformer\ItemDescriptionsResponseTransformerInterface;
use App\Application\ResponseTransformer\ItemsResponseTransformerInterface;
use App\Infrastructure\Solr\SolrClientInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class ItemsRepositoryTest extends TestCase
{
    /** @test */
    public function classConstruct()
    {
        $solrClient = $this->createMock(SolrClientInterface::class);
        $paginationCalculator = $this->createMock(PaginationCalculatorInterface::class);
        $responseTransformer = $this->createMock(ItemsResponseTransformerInterface::class);
        $descriptionsResponseTransformer = $this->createMock(ItemDescriptionsResponseTransformerInterface::class);
        $apkResponseTransformer = $this->createMock(ItemApksResponseTransformerInterface::class);
        $em = $this->createMock(EntityManagerInterface::class);

        $repository = new ItemsRepository($solrClient, $paginationCalculator, $responseTransformer, $descriptionsResponseTransformer, $apkResponseTransformer, $em);
        $this->assertInstanceOf(ItemsRepositoryInterface::class, $repository);
    }
}
