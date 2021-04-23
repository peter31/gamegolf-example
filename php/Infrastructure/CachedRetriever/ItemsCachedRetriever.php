<?php declare(strict_types=1);

namespace App\Infrastructure\CachedRetriever;

use App\Application\CachedRetriever\ItemsCachedRetrieverInterface;
use App\Application\Repository\ItemsRepositoryInterface;
use App\Application\ResponseModel\ItemApkResponseModel;
use App\Application\ResponseModel\ItemDescriptionResponseModel;
use App\Application\ResponseModel\ItemLikesStatisticsResponseModel;
use App\Application\ResponseModel\ItemNeighborsResponseModel;
use App\Application\ResponseModel\ItemResponseModel;
use App\Application\ResponseModel\ItemsPaginatedListResponseModel;
use App\Application\ResponseModel\ListResponseModel;
use App\Application\ResponseModel\PaginatedListResponseModel;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ItemsCachedRetriever implements ItemsCachedRetrieverInterface
{
    /** @var ItemsRepositoryInterface */
    private $itemsRepository;

    /** @var CacheInterface */
    private $cacheAdapter;

    /** @var SerializerInterface */
    private $serializer;

    public function __construct(ItemsRepositoryInterface $itemsRepository, CacheInterface $cacheAdapter, SerializerInterface $serializer)
    {
        $this->itemsRepository = $itemsRepository;
        $this->cacheAdapter = $cacheAdapter;
        $this->serializer = $serializer;
    }

    public function getTopDownloadableItems(string $languageCode, ?int $category = null, bool $onlyApk = false, int $limit = 20): ListResponseModel
    {
        return new ListResponseModel(
            $this->serializer->deserialize(
                $this->cacheAdapter->get(
                    sprintf('items_retriever.top_downlodable.%s.%s.%s.%s', $languageCode, $category, $onlyApk, $limit),
                    function (ItemInterface $item) use ($languageCode, $category, $onlyApk, $limit) {
                        $item->expiresAfter(3600 * 24);

                        return $this->serializer->serialize(
                            $this->itemsRepository->getTopDownloadableItems($languageCode, $category, $onlyApk, $limit)->getData(),
                            'json'
                        );
                    }
                ),
                sprintf('array<%s>', ItemResponseModel::class),
                'json'
            )
        );
    }

    public function getRelevant(string $languageCode, ?int $categoryId = null, int $page = 1, ?int $limit = 10): PaginatedListResponseModel
    {
        return $this->serializer->deserialize(
            $this->cacheAdapter->get(
                sprintf('items_retriever.relevant.%s.%s.%s.%s', $languageCode, $categoryId, $page, $limit),
                function (ItemInterface $item) use ($languageCode, $categoryId, $page, $limit) {
                    $item->expiresAfter(3600 * 24);

                    return $this->serializer->serialize(
                        $this->itemsRepository->getRelevant($languageCode, $categoryId, $page, $limit),
                        'json'
                    );
                }
            ),
            ItemsPaginatedListResponseModel::class,
            'json'
        );
    }

    public function getTopVoted(string $languageCode, ?int $categoryId = null, int $page = 1, ?int $limit = 10): PaginatedListResponseModel
    {
        return $this->serializer->deserialize(
            $this->cacheAdapter->get(
                sprintf('items_retriever.top_voted.%s.%s.%s.%s', $languageCode, $categoryId, $page, $limit),
                function (ItemInterface $item) use ($languageCode, $categoryId, $page, $limit) {
                    $item->expiresAfter(3600 * 24);

                    return $this->serializer->serialize(
                        $this->itemsRepository->getTopVoted($languageCode, $categoryId, $page, $limit),
                        'json'
                    );
                }
            ),
            ItemsPaginatedListResponseModel::class,
            'json'
        );
    }

    public function getPublicById(string $languageCode, int $id): ?ItemResponseModel
    {
        $item = $this->cacheAdapter->get(
            sprintf('items_retriever.public_by_id.%s.%s', $languageCode, $id),
            function (ItemInterface $item) use ($languageCode, $id) {
                return $this->serializer->serialize(
                    $this->itemsRepository->getPublicById($languageCode, $id),
                    'json',
                    (new SerializationContext())->setSerializeNull(true)
                );
            }
        );

        if ('null' === $item) {
            return null;
        }

        return $this->serializer->deserialize($item, ItemResponseModel::class, 'json');
    }

    public function getLikesStatistics(string $languageCode, int $itemId): ItemLikesStatisticsResponseModel
    {
        return $this->serializer->deserialize(
            $this->cacheAdapter->get(
                sprintf('items_retriever.likes_statistics.%s.%s', $languageCode, $itemId),
                function (ItemInterface $item) use ($languageCode, $itemId) {
                    return $this->serializer->serialize(
                        $this->itemsRepository->getLikesStatisticsByItemId($languageCode, $itemId),
                        'json'
                    );
                }
            ),
            ItemLikesStatisticsResponseModel::class,
            'json'
        );
    }

    public function getApksByItem(int $itemId, ?int $limit = null): ListResponseModel
    {
        return new ListResponseModel(
            $this->serializer->deserialize(
                $this->cacheAdapter->get(
                    sprintf('items_retriever.apks_by_item.%s.%s', $itemId, $limit),
                    function (ItemInterface $item) use ($itemId, $limit) {
                        return $this->serializer->serialize(
                            $this->itemsRepository->getApksByItem($itemId, $limit)->getData(),
                            'json'
                        );
                    }
                ),
                sprintf('array<%s>', ItemApkResponseModel::class),
                'json'
            )
        );
    }

    public function getNeighborsByItem(string $languageCode, int $itemId): ItemNeighborsResponseModel
    {
        return $this->serializer->deserialize(
            $this->cacheAdapter->get(
                sprintf('items_retriever.neighbors_by_item.%s.%s', $languageCode, $itemId),
                function (ItemInterface $item) use ($languageCode, $itemId) {
                    $item->expiresAfter(3600 * 12);

                    return $this->serializer->serialize(
                        $this->itemsRepository->getNeighborsByItem($languageCode, $itemId),
                        'json'
                    );
                }
            ),
            ItemNeighborsResponseModel::class,
            'json'
        );
    }

    public function getRelatedByItem(string $languageCode, int $itemId, int $limit): ListResponseModel
    {
        return new ListResponseModel(
            $this->serializer->deserialize(
                $this->cacheAdapter->get(
                    sprintf('items_retriever.related_by_item.%s.%s.%s', $languageCode, $itemId, $limit),
                    function (ItemInterface $item) use ($languageCode, $itemId, $limit) {
                        return $this->serializer->serialize(
                            $this->itemsRepository->getRelatedByItem($languageCode, $itemId, $limit)->getData(),
                            'json'
                        );
                    }
                ),
                sprintf('array<%s>', ItemResponseModel::class),
                'json'
            )
        );
    }

    public function getTopVotedByItem(string $languageCode, int $itemId, int $limit): ListResponseModel
    {
        return new ListResponseModel(
            $this->serializer->deserialize(
                $this->cacheAdapter->get(
                    sprintf('items_retriever.top_voted_by_item.%s.%s.%s', $languageCode, $itemId, $limit),
                    function (ItemInterface $item) use ($languageCode, $itemId, $limit) {
                        return $this->serializer->serialize(
                            $this->itemsRepository->getTopVotedByItem($languageCode, $itemId, $limit)->getData(),
                            'json'
                        );
                    }
                ),
                sprintf('array<%s>', ItemResponseModel::class),
                'json'
            )
        );
    }

    public function getDescriptionsByItem(string $languageCode, int $itemId): ListResponseModel
    {
        return new ListResponseModel(
            $this->serializer->deserialize(
                $this->cacheAdapter->get(
                    sprintf('items_retriever.descriptions_by_item.%s.%s', $languageCode, $itemId),
                    function (ItemInterface $item) use ($languageCode, $itemId) {
                        return $this->serializer->serialize(
                            $this->itemsRepository->getDescriptionsByItem($languageCode, $itemId)->getData(),
                            'json'
                        );
                    }
                ),
                sprintf('array<%s>', ItemDescriptionResponseModel::class),
                'json'
            )
        );
    }
}
