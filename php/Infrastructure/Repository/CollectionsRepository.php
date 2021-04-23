<?php declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Application\Repository\CollectionsRepositoryInterface;
use App\Application\ResponseModel\CollectionResponseModel;
use App\Application\ResponseModel\ListResponseModel;
use App\Application\ResponseTransformer\ItemsResponseTransformerInterface;
use App\Common\Al\Collections\Domain\Collection;
use App\Infrastructure\Solr\SolrClientInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;

class CollectionsRepository implements CollectionsRepositoryInterface
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var SolrClientInterface */
    private $collectionsSolrClient;

    /** @var ItemsResponseTransformerInterface */
    private $itemsCombinedResponseTransformer;

    public function __construct(
        EntityManagerInterface $em,
        SolrClientInterface $collectionsSolrClient,
        ItemsResponseTransformerInterface $itemsCombinedResponseTransformer
    ) {
        $this->em = $em;
        $this->collectionsSolrClient = $collectionsSolrClient;
        $this->itemsCombinedResponseTransformer = $itemsCombinedResponseTransformer;
    }

    /**
     * @param string $languageCode
     * @param int $limit
     * @return CollectionResponseModel[]
     */
    public function getLatest(string $languageCode, int $limit): ListResponseModel
    {
        /** @var ServiceEntityRepository $repository */
        $repository = $this->em->getRepository(Collection::class);

        return new ListResponseModel(
            array_map(
                function (Collection $collection) {
                    return CollectionResponseModel::fromDomainCollection($collection);
                },
                $repository->createQueryBuilder('c')
                    ->where('c.language = :languageCode')
                    ->setParameter('languageCode', $languageCode)
                    ->andWhere('c.isPublished = 1')
                    ->andWhere('c.isIndexed = 1')
                    ->orderBy('c.updatedAt', 'DESC')
                    ->setMaxResults($limit)
                    ->getQuery()->getResult()
            )
        );
    }

    public function getLatestWithItems(string $languageCode, int $limit, int $itemsLimit): ListResponseModel
    {
        return new ListResponseModel(
            array_map(
                function(CollectionResponseModel $collectionModel) use ($languageCode, $itemsLimit) {
                    $response = $this->collectionsSolrClient->select(
                        $this->collectionsSolrClient->createSelect([
                            'query' => sprintf(
                                'is_public_list_%s:true AND collections_id_%s:%d',
                                $languageCode,
                                $languageCode,
                                $collectionModel->getId()
                            ),
                            'sort' => [
                                sprintf('orden_%s', $languageCode) => 'desc',
                                'fecha_subida' => 'desc',
                                'nr_votes' => 'desc',
                            ],
                            'start' => 0,
                            'responsewriter' => "phps",
                            'rows' => $itemsLimit,
                        ])
                    );

                    return $collectionModel->setItems(
                        $this->itemsCombinedResponseTransformer->transform($response->getDocuments(), $languageCode)
                    );
                },
                $this->getLatest($languageCode, $limit)->getData()
            )
        );
    }
}
