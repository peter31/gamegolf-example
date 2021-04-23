<?php declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Application\Repository\CollectionsRepositoryInterface;
use App\Application\ResponseTransformer\ItemsResponseTransformerInterface;
use App\Infrastructure\Solr\SolrClientInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class CollectionsRepositoryTest extends TestCase
{
    /** @test */
    public function classConstruct()
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $solrClient = $this->createMock(SolrClientInterface::class);
        $responseTransformer = $this->createMock(ItemsResponseTransformerInterface::class);

        $repository = new CollectionsRepository($em, $solrClient, $responseTransformer);
        $this->assertInstanceOf(CollectionsRepositoryInterface::class, $repository);
    }
}
