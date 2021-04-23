<?php declare(strict_types=1);

namespace App\Infrastructure\CachedRetriever;

use App\Application\CachedRetriever\NewsCachedRetrieverInterface;
use App\Application\Repository\NewsRepositoryInterface;
use App\Application\ResponseModel\ListResponseModel;
use App\Application\ResponseModel\NewsResponseModel;
use JMS\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class NewsCachedRetriever implements NewsCachedRetrieverInterface
{
    /** @var NewsRepositoryInterface */
    private $newsRepository;

    /** @var CacheInterface */
    private $cacheAdapter;

    /** @var SerializerInterface */
    private $serializer;

    public function __construct(NewsRepositoryInterface $newsRepository, CacheInterface $cacheAdapter, SerializerInterface $serializer)
    {
        $this->newsRepository = $newsRepository;
        $this->cacheAdapter = $cacheAdapter;
        $this->serializer = $serializer;
    }

    public function getLatest(string $languageCode, int $limit, ?int $categoryId = null): ListResponseModel
    {
        return new ListResponseModel(
            $this->serializer->deserialize(
                $this->cacheAdapter->get(
                    sprintf('news_retriever.news.%s.%s.%s', $languageCode, $limit, $categoryId),
                    function (ItemInterface $item) use ($languageCode, $limit, $categoryId) {
                        $item->expiresAfter(3600 * 24);

                        return $this->serializer->serialize(
                            $this->newsRepository->getLatest($languageCode, $limit)->getData(),
                            'json'
                        );
                    }
                ),
                sprintf('array<%s>', NewsResponseModel::class),
                'json'
            )
        );
    }

    public function getByItem(string $languageCode, int $itemId, int $limit): ListResponseModel
    {
        return new ListResponseModel(
            $this->serializer->deserialize(
                $this->cacheAdapter->get(
                    sprintf('news_retriever.news.%s.%s.%s', $languageCode, $itemId, $limit),
                    function (ItemInterface $item) use ($languageCode, $itemId, $limit) {
                        return $this->serializer->serialize(
                            $this->newsRepository->getByItem($languageCode, $itemId, $limit)->getData(),
                            'json'
                        );
                    }
                ),
                sprintf('array<%s>', NewsResponseModel::class),
                'json'
            )
        );
    }
}
