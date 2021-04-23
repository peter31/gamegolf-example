<?php declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Application\Exception\NotFoundException;
use App\Application\Pagination\PaginationCalculatorInterface;
use App\Application\Pagination\PaginationModel;
use App\Application\Repository\ItemsRepositoryInterface;
use App\Application\ResponseModel\ItemLikesStatisticsResponseModel;
use App\Application\ResponseModel\ItemNeighborsResponseModel;
use App\Application\ResponseModel\ItemResponseModel;
use App\Application\ResponseModel\ListResponseModel;
use App\Application\ResponseModel\PaginatedListResponseModel;
use App\Application\ResponseTransformer\ItemApksResponseTransformerInterface;
use App\Application\ResponseTransformer\ItemDescriptionsResponseTransformerInterface;
use App\Application\ResponseTransformer\ItemsResponseTransformerInterface;
use App\Common\Al\Items\Domain\ItemLike;
use App\Infrastructure\Solr\SolrClientInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;

class ItemsRepository implements ItemsRepositoryInterface
{
    /** @var SolrClientInterface */
    private $itemsSolrClient;

    /** @var PaginationCalculatorInterface */
    private $paginationCalculator;

    /** @var ItemsResponseTransformerInterface */
    private $itemsCombinedResponseTransformer;

    /** @var ItemDescriptionsResponseTransformerInterface */
    private $itemDescriptionsResponseTransformer;

    /** @var  */
    private $itemApksResponseTransformer;

    /** @var EntityManagerInterface */
    private $em;

    public function __construct(
        SolrClientInterface $itemsSolrClient,
        PaginationCalculatorInterface $paginationCalculator,
        ItemsResponseTransformerInterface $itemsCombinedResponseTransformer,
        ItemDescriptionsResponseTransformerInterface $itemDescriptionsResponseTransformer,
        ItemApksResponseTransformerInterface $itemApksResponseTransformer,
        EntityManagerInterface $em
    ) {
        $this->itemsSolrClient = $itemsSolrClient;
        $this->paginationCalculator = $paginationCalculator;
        $this->itemsCombinedResponseTransformer = $itemsCombinedResponseTransformer;
        $this->itemDescriptionsResponseTransformer = $itemDescriptionsResponseTransformer;
        $this->itemApksResponseTransformer = $itemApksResponseTransformer;
        $this->em = $em;
    }

    public function searchItems(string $languageCode, string $term, int $page = 1, ?int $limit = 10): PaginatedListResponseModel
    {
        $query = str_replace(' ', ' AND ', $term);

        if ('en' === $languageCode){
            $nameQuerySearch = sprintf('name_%s: ((%s*) OR (%s))', $languageCode, $query, $query);
        } else {
            $nameQuerySearch = sprintf('( (name_%s:((%s*) OR (%s))^100) OR (name_en:(%s*))^10 )', $languageCode, $query, $query, $query);
        }

        $resultQuery = sprintf('%s AND is_public_list_%s:1', $nameQuerySearch, $languageCode);

        $totalItems = $this->itemsSolrClient->select(
            $this->itemsSolrClient->createSelect(
                [
                    'responsewriter' => "phps",
                    'query' => $resultQuery,
                ]
            )
        )->getNumFound();

        if ($totalItems > 0) {
            if ($totalItems > $limit * 3) {
                $totalItems = $limit * 3;
            }

            $paginationData = $this->paginationCalculator->calculate($totalItems, $limit, $page);

            $response = $this->itemsSolrClient->select(
                $this->itemsSolrClient->createSelect(
                    [
                        'query' => $resultQuery,
                        'responsewriter' => "phps",
                        'sort' => [
                            'nr_votes' => 'desc'
                        ],
                        'start' => $paginationData->getOffset(),
                        'rows' => $paginationData->getLimit()
                    ]
                )
            );

            return $this->itemsCombinedResponseTransformer->transformListModel(
                new PaginatedListResponseModel(
                    $paginationData,
                    $response->getDocuments()
                ),
                $languageCode
            );
        }

        return new PaginatedListResponseModel(new PaginationModel(), []);
    }

    public function getTopDownloadableItems(string $languageCode, ?int $category = null, bool $onlyApk = false, int $limit = 20): ListResponseModel
    {
        $querySegments = [];
        if ($onlyApk) {
            $querySegments[] = sprintf('+is_public_only_apk_%s:true', $languageCode);
        } else {
            $querySegments[] = sprintf('+is_public_list_%s:true', $languageCode);
        }

        if (null !== $category) {
            $querySegments[] = sprintf('+categories_id:%s', $category);
        }

        return new ListResponseModel(
            $this->itemsCombinedResponseTransformer->transform(
                $this->itemsSolrClient->select(
                    $this->itemsSolrClient->createSelect([
                        'sort' => [
                            sprintf('is_top_download_%s', $languageCode) => 'desc',
                            sprintf('monthly_downloads_%s', $languageCode) => 'desc',
                        ],
                        'rows' => $limit
                    ])
                    ->addFilterQuery(['key' => 'top_download', 'query' => implode(' ', $querySegments)])
                )->getDocuments(),
                $languageCode
            )
        );
    }

    public function getById(string $languageCode, int $id): ?ItemResponseModel
    {
        $query = sprintf('+id:%d', $id);

        $response = $this->itemsSolrClient->select(
            $this->itemsSolrClient->createSelect(
                [
                    'query' => $query,
                    'responsewriter' => "phps"
                ]
            )
        );

        $data = $this->itemsCombinedResponseTransformer->transform(
            $response->getDocuments(),
            $languageCode
        );

        return count($data) ? reset($data) : null;
    }

    public function getRelevant(string $languageCode, ?int $categoryId = null, int $page = 1, ?int $limit = 10): PaginatedListResponseModel
    {
        $query = sprintf(
            'is_public_list_%s:true AND is_relevant_%s: true AND -id:969025 %s',
            $languageCode,
            $languageCode,
            null !== $categoryId ? sprintf('+categories_id:%d', $categoryId): ''
        );

        $totalItems = $this->itemsSolrClient->select(
            $this->itemsSolrClient->createSelect(
                [
                    'responsewriter' => "phps",
                    'query' => $query,
                ]
            )
        )->getNumFound();

        if ($totalItems > 0) {
            $paginationModel = $this->paginationCalculator->calculate($totalItems, $limit, $page);

            $response = $this->itemsSolrClient->select(
                $this->itemsSolrClient->createSelect(
                    [
                        'query' => $query,
                        'responsewriter' => "phps",
                        'sort' => [
                            sprintf('marked_relevant_at_%s', $languageCode) => 'desc'
                        ],
                        'start' => $paginationModel->getOffset(),
                        'rows' => $paginationModel->getLimit()
                    ]
                )
            );

            return $this->itemsCombinedResponseTransformer->transformListModel(
                new PaginatedListResponseModel(
                    $paginationModel,
                    $response->getDocuments()
                ),
                $languageCode
            );
        }

        return new PaginatedListResponseModel(new PaginationModel(), []);
    }

    public function getTopVoted(string $languageCode, ?int $categoryId = null, int $page = 1, ?int $limit = 10): PaginatedListResponseModel
    {
        $query = sprintf(
            'is_public_list_%s:true %s',
            $languageCode,
            null !== $categoryId ? sprintf(' AND categories_id:%d', $categoryId): ''
        );

        $filterQuery = ['key' => 'relevant', 'query' => sprintf('+is_relevant_%s: true', $languageCode)];

        $totalItems = $this->itemsSolrClient->select(
            $this->itemsSolrClient->createSelect(
                [
                    'responsewriter' => "phps",
                    'query' => $query,
                ]
            )->setFilterQueries([$filterQuery])
        )->getNumFound();

        if ($totalItems > 0) {
            $paginationModel = $this->paginationCalculator->calculate($totalItems, $limit, $page);

            $response = $this->itemsSolrClient->select(
                $this->itemsSolrClient->createSelect(
                    [
                        'query' => $query,
                        'responsewriter' => "phps",
                        'sort' => [
                            'score' => 'desc',
                            'updated_at' => 'desc',
                        ],
                        'start' => $paginationModel->getOffset(),
                        'rows' => $paginationModel->getLimit()
                    ]
                )->addFilterQuery($filterQuery)
            );

            return $this->itemsCombinedResponseTransformer->transformListModel(
                new PaginatedListResponseModel(
                    $paginationModel,
                    $response->getDocuments()
                ),
                $languageCode
            );
        }

        return new PaginatedListResponseModel(new PaginationModel(), []);
    }

    public function getPublicById(string $languageCode, int $id): ?ItemResponseModel
    {
        $response = $this->itemsSolrClient->select(
            $this->itemsSolrClient->createSelect(
                [
                    'query' => sprintf('is_public_list_%s:true AND id:%s', $languageCode, $id),
                    'responsewriter' => "phps",
                    'rows' => 1
                ]
            )
        );

        return $this->itemsCombinedResponseTransformer->transform($response->getDocuments(), $languageCode)[0] ?? null;
    }

    public function getLikesStatisticsByItemId(string $languageCode, int $itemId): ItemLikesStatisticsResponseModel
    {
        /** @var ServiceEntityRepository $itemLikesRepository */
        $itemLikesRepository = $this->em->getRepository(ItemLike::class);
        $data = $itemLikesRepository->createQueryBuilder('il')
            ->select('il.type, COUNT(il.id) as likes_count')
            ->where('il.item = :itemId')
            ->setParameter('itemId', $itemId)
            ->groupBy('il.type')
            ->getQuery()->getResult();

        return array_reduce(
            $data,
            function (ItemLikesStatisticsResponseModel $carry, array $typeData): ItemLikesStatisticsResponseModel {
                return $carry->setByType($typeData['type'], $typeData['likes_count']);
            },
            new ItemLikesStatisticsResponseModel()
        );
    }

    public function getApksByItem(int $itemId, ?int $limit = null): ListResponseModel
    {
        $query = sprintf('
            SELECT a.*, v.data, v.url_virus_total
            FROM tbl_apk_item a
            LEFT JOIN tbl_virus_total v on v.id_apk_item = a.id
            WHERE a.item_id = %d and a.is_processed = 1 AND a.is_dangerous = 0
            GROUP BY a.version
            ORDER BY CAST(SUBSTRING_INDEX(a.version, ".", 1) AS unsigned) DESC,
                CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(a.version, ".", 2), ".", -1) as unsigned) DESC,
                CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(a.version, ".", 3), ".", -1) as unsigned) DESC
                %s',
            $itemId,
            null !== $limit ? sprintf('limit %d', $limit) : ''
        );

        return new ListResponseModel(
            $this->itemApksResponseTransformer->transform(
                $this->em->getConnection()->executeQuery($query)->fetchAllAssociative()
            )
        );
    }

    public function getNeighborsByItem(string $languageCode, int $itemId): ItemNeighborsResponseModel
    {
        $item = $this->getPublicById($languageCode, $itemId);
        if (null === $item) {
            throw new NotFoundException(sprintf('Item with id "%s" and language code "%s" not found', $itemId, $languageCode));
        }

        $baseQuery = sprintf(
            'is_public_list_%s:true %s AND (is_top_download_%s:1 OR monthly_downloads_%s: [1 TO *])',
            $languageCode,
            count($item->getCategoriesId()) ? sprintf('AND categories_id:%d', $item->getCategoriesId()[0]) : '',
            $languageCode,
            $languageCode
        );

        $sortFields = [
            'score' => 'desc',
            sprintf('is_top_download_%s', $languageCode) => 'desc',
            'id_int' => 'asc',
            sprintf('monthly_downloads_%s', $languageCode) => 'desc',
        ];

        $nextItemResponse = $this->itemsSolrClient->select(
            $this->itemsSolrClient->createSelect(
                [
                    'query' => sprintf('%s AND  id_int:[%s TO *]', $baseQuery, $itemId),
                    'responsewriter' => "phps",
                    'sort' => $sortFields,
                    'rows' => 1
                ]
            )->addFilterQuery(['key' => '-id', 'query' => sprintf('-id: %d', $itemId)])
        );

        if ($nextItemResponse->getNumFound() === 0) {
            $nextItemResponse = $this->itemsSolrClient->select(
                $this->itemsSolrClient->createSelect(
                    [
                        'query' => sprintf('%s AND id_int:[1 TO *]', $baseQuery),
                        'responsewriter' => "phps",
                        'sort' => $sortFields,
                        'rows' => 1
                    ]
                )->addFilterQuery(['key' => '-id', 'query' => sprintf('-id: %d', $itemId)])
            );
        }

        $nextItem = $prevItem = null;
        if ($nextItemResponse->getNumFound() > 0) {
            $nextItem = $this->itemsCombinedResponseTransformer->transform($nextItemResponse->getDocuments(), $languageCode)[0];

            $prevItemResponse = $this->itemsSolrClient->select(
                $this->itemsSolrClient->createSelect(
                    [
                        'query' => sprintf('%s AND  id_int:[* TO %s]', $baseQuery, $itemId),
                        'responsewriter' => "phps",
                        'sort' => $sortFields,
                        'rows' => 1
                    ]
                )->addFilterQuery(['key' => '-id', 'query' => sprintf('-id: %d -id:%d', $itemId, $nextItem->getId())])
            );

            if ($prevItemResponse->getNumFound() === 0) {
                $prevItemResponse = $this->itemsSolrClient->select(
                    $this->itemsSolrClient->createSelect(
                        [
                            'query' => sprintf('%s AND  id_int:[%s TO *]', $baseQuery, $itemId),
                            'responsewriter' => "phps",
                            'sort' => $sortFields,
                            'rows' => 1
                        ]
                    )->addFilterQuery(['key' => '-id', 'query' => sprintf('-id: %d -id:%d', $itemId, $nextItem->getId())])
                );
            }

            if ($prevItemResponse->getNumFound() > 0) {
                $prevItem = $this->itemsCombinedResponseTransformer->transform($prevItemResponse->getDocuments(), $languageCode)[0];
            }
        }

        return new ItemNeighborsResponseModel($prevItem, $nextItem);
    }

    public function getRelatedByItem(string $languageCode, int $itemId, int $limit): ListResponseModel
    {
        $item = $this->getPublicById($languageCode, $itemId);
        if (null === $item) {
            throw new NotFoundException(sprintf('Item with id "%s" and language code "%s" not found', $itemId, $languageCode));
        }

        $response = $this->itemsSolrClient->select(
            $this->itemsSolrClient->createSelect(
                [
                    'query' => sprintf(
                        'is_public_list_%s:true AND is_relevant_%s:true AND (%s)',
                        $languageCode,
                        $languageCode,
                        $this->prepareSubqueryByCategories($item)
                    ),
                    'responsewriter' => "phps",
                    'sort' => [
                        'score' => 'desc',
                        'updated_at' => 'desc',
                    ],
                    'rows' => $limit
                ]
            )->addFilterQuery(['key' => '-id', 'query' => sprintf('-id: %d', $itemId)])
        );

        if ($response->getNumFound()) {
            return new ListResponseModel(
                $this->itemsCombinedResponseTransformer->transform($response->getDocuments(), $languageCode)
            );
        }

        return new ListResponseModel([]);
    }

    public function getTopVotedByItem(string $languageCode, int $itemId, int $limit): ListResponseModel
    {
        $item = $this->getPublicById($languageCode, $itemId);
        if (null === $item) {
            throw new NotFoundException(sprintf('Item with id "%s" and language code "%s" not found', $itemId, $languageCode));
        }

        $response = $this->itemsSolrClient->select(
            $this->itemsSolrClient->createSelect(
                [
                    'query' => sprintf(
                        'is_public_list_%s:true AND (is_top_download_%s:1 OR monthly_downloads_%s: [1 TO *]) AND (%s)',
                        $languageCode,
                        $languageCode,
                        $languageCode,
                        $this->prepareSubqueryByCategories($item)
                    ),
                    'responsewriter' => "phps",
                    'sort' => [
                        'score' => 'desc',
                        sprintf('monthly_downloads_%s', $languageCode) => 'desc',
                    ],
                    'rows' => $limit
                ]
            )->addFilterQuery(['key' => '-id', 'query' => sprintf('-id: %d', $itemId)])
        );

        if ($response->getNumFound()) {
            return new ListResponseModel(
                $this->itemsCombinedResponseTransformer->transform($response->getDocuments(), $languageCode)
            );
        }

        return new ListResponseModel([]);
    }

    private function prepareSubqueryByCategories(ItemResponseModel $item)
    {
        $counter = 0;
        return implode(
            ' OR ',
            array_map(
                function (int $categoryId) use ($counter): string {
                    $counter++;
                    return sprintf('categories_id:%s^%s', $categoryId, $counter);
                },
                $item->getCategoriesId()
            )
        );
    }

    public function getDescriptionsByItem(string $languageCode, int $itemId): ListResponseModel
    {
        $item = $this->getPublicById($languageCode, $itemId);
        if (null === $item) {
            throw new NotFoundException(sprintf('Item with id "%s" and language code "%s" not found', $itemId, $languageCode));
        }

        $query = sprintf(
            'SELECT d.id, d.title, d.description, d.description_type_id, dt.name as description_type_name, d.user_id
            FROM tbl_descriptions d
            LEFT JOIN tbl_description_types dt on dt.description_type_id=d.description_type_id
            WHERE
                d.language_code = "%s" AND
                dt.type_id = %d AND
                dt.language_code = "%s" AND
                item_id=%d AND
                est_publicacion = 1 AND
                d.description_type_id IN (1, 2, 3, 7, 8, 9)
            ORDER BY FIELD(d.description_type_id, 1, 9, 2, 3, 8), LENGTH(SUBSTRING_INDEX(d.title, "-", 1)), d.title',
            $languageCode,
            $item->getTypeId(),
            $languageCode,
            $itemId
        );

        return new ListResponseModel(
            $this->itemDescriptionsResponseTransformer->transform(
                $this->em->getConnection()->executeQuery($query)->fetchAllAssociative()
            )
        );
    }
}
