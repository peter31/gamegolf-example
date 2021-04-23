<?php declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Application\Repository\NewsRepositoryInterface;
use App\Application\ResponseModel\ListResponseModel;
use App\Application\ResponseModel\NewsResponseModel;
use App\Application\ResponseTransformer\NewsResponseTransformerInterface;
use App\Common\Al\Items\Domain\ItemNews;
use App\Common\Wordpress\Post\Domain\WpPost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;

class NewsRepository implements NewsRepositoryInterface
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var NewsResponseTransformerInterface */
    private $newsResponseTransformer;

    public function __construct(
        EntityManagerInterface $em,
        NewsResponseTransformerInterface $newsResponseTransformer
    ) {
        $this->em = $em;
        $this->newsResponseTransformer = $newsResponseTransformer;
    }

    public function getById(string $languageCode, int $id): ?NewsResponseModel
    {
        /** @var ServiceEntityRepository $postsRepository */
        $postsRepository = $this->em->getRepository(WpPost::class);

        $items = $this->newsResponseTransformer->transform(
            $postsRepository->createQueryBuilder('p')
                ->where('p.locale.value = :languageCode')
                ->setParameter('languageCode', $languageCode)
                ->andWhere('p.id = :postId')
                ->setParameter('postId', $id)
                ->setMaxResults(1)
                ->getQuery()->getResult(),
            $languageCode
        );

        return count($items) ? reset($items) : null;
    }

    /**
     * @param string $languageCode
     * @param int $limit
     * @param int|null $categoryId
     * @return NewsResponseModel[]
     * @throws \Exception
     */
    public function getLatest(string $languageCode, int $limit, ?int $categoryId = null): ListResponseModel
    {
        /** @var ServiceEntityRepository $postsRepository */
        $postsRepository = $this->em->getRepository(WpPost::class);

        return new ListResponseModel(
            $this->newsResponseTransformer->transform(
                $postsRepository->createQueryBuilder('p')
                    ->where('p.locale.value = :languageCode')
                    ->setParameter('languageCode', $languageCode)
                    ->orderBy('p.publishedOn', 'DESC')
                    ->setMaxResults($limit)
                    ->getQuery()->getResult(),
                $languageCode
            )
        );
    }

    public function getByItem(string $languageCode, int $itemId, int $limit): ListResponseModel
    {
        /** @var ServiceEntityRepository $repository */
        $repository = $this->em->getRepository(WpPost::class);

        /** @var ItemNews[] $newsData */
        return new ListResponseModel(
            $this->newsResponseTransformer->transform(
                $repository->createQueryBuilder('p')
                    ->leftJoin(ItemNews::class, 'ni', Join::WITH, 'ni.postId = p.postId')
                    ->where('ni.item = :itemId')
                    ->setParameter('itemId', $itemId)
                    ->andWhere('ni.languageCode = :languageCode')
                    ->andWhere('p.locale.value = :languageCode')
                    ->setParameter('languageCode', $languageCode)
                    ->orderBy('ni.id', 'DESC')
                    ->setMaxResults($limit)
                    ->getQuery()->getResult()
            )
        );
    }
}
