<?php declare(strict_types=1);

namespace App\Infrastructure\CachedRetriever;

use App\Application\CachedRetriever\ItemOpinionsCachedRetrieverInterface;
use App\Application\Repository\ItemOpinionsRepositoryInterface;
use App\Application\ResponseModel\ItemOpinionsPaginatedListResponseModel;
use App\Application\ResponseModel\ListResponseModel;
use App\Application\ResponseModel\PaginatedListResponseModel;
use JMS\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ItemOpinionsCachedRetriever implements ItemOpinionsCachedRetrieverInterface
{
    /** @var ItemOpinionsRepositoryInterface */
    private $itemOpinionsRepository;

    /** @var CacheInterface */
    private $cacheAdapter;

    /** @var SerializerInterface */
    private $serializer;

    public function __construct(ItemOpinionsRepositoryInterface $itemOpinionsRepository, CacheInterface $cacheAdapter, SerializerInterface $serializer)
    {
        $this->itemOpinionsRepository = $itemOpinionsRepository;
        $this->cacheAdapter = $cacheAdapter;
        $this->serializer = $serializer;
    }

    public function getLatestWithItems(string $languageCode, int $limit, int $typeId, ?int $categoryId = null): ListResponseModel
    {
        return new ListResponseModel(
            $this->serializer->deserialize(
                $this->cacheAdapter->get(
                    sprintf('item_opinions_retriever.latest.%s.%s.%s.%s', $languageCode, $limit, $typeId, $categoryId),
                    function (ItemInterface $item) use ($languageCode, $limit, $typeId, $categoryId) {
                        return $this->serializer->serialize(
                            $this->itemOpinionsRepository->getLatestWithItems($languageCode, $limit, $typeId, $categoryId)->getData(),
                            'json'
                        );
                    }
                ),
                'array<App\Application\ResponseModel\ItemOpinionResponseModel>',
                'json'
            )
        );
    }

    public function getByItemId(string $languageCode, int $itemId, int $page, int $limit): PaginatedListResponseModel
    {
        return $this->serializer->deserialize(
            $this->cacheAdapter->get(
                sprintf('item_opinions_retriever.by_item_id.%s.%s.%s.%s', $languageCode, $itemId, $page, $limit),
                function (ItemInterface $item) use ($languageCode, $itemId, $page, $limit) {
                    return $this->serializer->serialize(
                        $this->itemOpinionsRepository->getByItemId($languageCode, $itemId, $page, $limit),
                        'json'
                    );
                }
            ),
            ItemOpinionsPaginatedListResponseModel::class,
            'json'
        );
    }
}
