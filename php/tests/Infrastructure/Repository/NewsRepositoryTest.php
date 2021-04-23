<?php declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Application\CachedRetriever\ProjectDomainCachedRetrieverInterface;
use App\Application\Repository\NewsRepositoryInterface;
use App\Application\ResponseTransformer\NewsResponseTransformerInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class NewsRepositoryTest extends TestCase
{
    /** @test */
    public function classConstruct()
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $responseTransformer = $this->createMock(NewsResponseTransformerInterface::class);

        $repository = new NewsRepository($em, $responseTransformer);
        $this->assertInstanceOf(NewsRepositoryInterface::class, $repository);
    }
}
