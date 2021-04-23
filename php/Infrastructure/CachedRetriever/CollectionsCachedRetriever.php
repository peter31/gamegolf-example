<?php declare(strict_types=1);

namespace App\Infrastructure\CachedRetriever;

use App\Application\CachedRetriever\CollectionsCachedRetrieverInterface;
use App\Application\Repository\CollectionsRepositoryInterface;
use App\Application\ResponseModel\ListResponseModel;
use JMS\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class CollectionsCachedRetriever implements CollectionsCachedRetrieverInterface
{
    /** @var CollectionsRepositoryInterface */
    private $collectionsRepository;

    /** @var CacheInterface */
    private $cacheAdapter;

    /** @var SerializerInterface */
    private $serializer;

    public function __construct(CollectionsRepositoryInterface $collectionsRepository, CacheInterface $cacheAdapter, SerializerInterface $serializer)
    {
        $this->collectionsRepository = $collectionsRepository;
        $this->cacheAdapter = $cacheAdapter;
        $this->serializer = $serializer;
    }

    public function getLatestWithItems(string $languageCode, int $limit, int $itemsLimit): ListResponseModel
    {
        return new ListResponseModel(
            $this->serializer->deserialize(
                $this->cacheAdapter->get(
                    sprintf('collections_retriever.latest_with_items.%s.%s.%s', $languageCode, $limit, $itemsLimit),
                    function (ItemInterface $item) use ($languageCode, $limit, $itemsLimit) {
                        return $this->serializer->serialize(
                            $this->collectionsRepository->getLatestWithItems($languageCode, $limit, $itemsLimit)->getData(),
                            'json'
                        );
                    }
                ),
                'array<App\Application\ResponseModel\CollectionResponseModel>',
                'json'
            )
        );
    }
}