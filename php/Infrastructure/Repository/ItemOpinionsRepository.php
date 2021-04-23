<?php declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Application\Pagination\PaginationCalculatorInterface;
use App\Application\Pagination\PaginationModel;
use App\Application\Repository\ItemOpinionsRepositoryInterface;
use App\Application\Repository\ItemsRepositoryInterface;
use App\Application\ResponseModel\ItemOpinionResponseModel;
use App\Application\ResponseModel\ItemResponseModel;
use App\Application\ResponseModel\ListResponseModel;
use App\Application\ResponseModel\PaginatedListResponseModel;
use App\Application\ResponseTransformer\ItemOpinionsResponseTransformerInterface;
use App\Common\Al\Items\Domain\ItemOpinion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;

class ItemOpinionsRepository implements ItemOpinionsRepositoryInterface
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var ItemsRepositoryInterface */
    private $itemsRepository;

    /** @var PaginationCalculatorInterface */
    private $paginationCalculator;

    /** @var ItemOpinionsResponseTransformerInterface */
    private $itemOpinionsResponseTransformer;

    public function __construct(
        EntityManagerInterface $em,
        ItemsRepositoryInterface $itemsRepository,
        PaginationCalculatorInterface $paginationCalculator,
        ItemOpinionsResponseTransformerInterface $itemOpinionsResponseTransformer
    ) {
        $this->em = $em;
        $this->itemsRepository = $itemsRepository;
        $this->paginationCalculator = $paginationCalculator;
        $this->itemOpinionsResponseTransformer = $itemOpinionsResponseTransformer;
    }

    public function getLatest(string $languageCode, int $limit, int $typeId, ?int $categoryId = null): ListResponseModel
    {
        /** @var ServiceEntityRepository $repository */
        $repository = $this->em->getRepository(ItemOpinion::class);
        $qb = $repository->createQueryBuilder('o')
            ->leftJoin('o.item', 'i')
            ->where('o.itemTypeId = :itemTypeId')
            ->setParameter('itemTypeId', $typeId)
            ->andWhere('o.isValidated = 1')
            ->andWhere('o.parentId = 0')
            ->andWhere('o.languageCode = :languageCode')
            ->setParameter('languageCode', $languageCode)
            ->andWhere('BIT_AND(i.statuses.statusBr, :isPublished) = :isPublished')
            ->setParameter('isPublished', ItemResponseModel::PUBLICATION_PUBLIC)
            ->andWhere('BIT_AND(i.statuses.statusBr, :isShowAdseanse) = :isShowAdseanse')
            ->setParameter('isShowAdseanse', ItemResponseModel::PUBLICATION_SHOW_ADSENSE)
            ->orderBy('o.createdAt', 'DESC')
            ->setMaxResults($limit);

        if (null !== $categoryId) {
            $qb->leftJoin('i.categories', 'ic')
                ->andWhere('ic.id = :categporyId')
                ->setParameter('categporyId', $categoryId);
        }

        return new ListResponseModel(
            $this->itemOpinionsResponseTransformer->transform($qb->getQuery()->getResult())
        );
    }

    // TODO Add user, likes, image and item data if missing in Solr
    public function getLatestWithItems(string $languageCode, int $limit, int $typeId, ?int $categoryId = null): ListResponseModel
    {
        return new ListResponseModel(
            array_map(
                function(ItemOpinionResponseModel $itemOpinionModel) use ($languageCode) {
                    return $itemOpinionModel->setItem(
                        $this->itemsRepository->getById($languageCode, $itemOpinionModel->getItemId())
                    );
                },
                $this->getLatest($languageCode, $limit, $typeId, $categoryId)->getData()
            )
        );
    }

    public function getByItemId(string $languageCode, int $itemId, int $page, int $limit): PaginatedListResponseModel
    {
        $data = $this->em->getRepository(ItemOpinion::class)->createQueryBuilder('io')
            ->where('io.parentId = 0')
            ->andWhere('io.isValidated = 1')
            ->andWhere('io.item = :itemId')
            ->setParameter('itemId', $itemId)
            ->andWhere('io.languageCode = :languageCode')
            ->setParameter('languageCode', $languageCode)
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()->getResult();

        return new PaginatedListResponseModel(
            $this->paginationCalculator->calculate(count($data), $limit, $page),
            $this->itemOpinionsResponseTransformer->transform($data)
        );
    }
}
