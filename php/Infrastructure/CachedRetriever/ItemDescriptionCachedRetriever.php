<?php declare(strict_types=1);

namespace App\Infrastructure\CachedRetriever;

use App\Application\CachedRetriever\ItemDescriptionCachedRetrieverInterface;
use App\Application\Repository\ItemDescriptionRepositoryInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ItemDescriptionCachedRetriever implements ItemDescriptionCachedRetrieverInterface
{
    /** @var ItemDescriptionRepositoryInterface */
    private $itemDescriptionRepository;

    /** @var CacheInterface */
    private $cacheAdapter;

    public function __construct(ItemDescriptionRepositoryInterface $itemDescriptionRepository, CacheInterface $cacheAdapter)
    {
        $this->itemDescriptionRepository = $itemDescriptionRepository;
        $this->cacheAdapter = $cacheAdapter;
    }

    public function getTextByIdAndLanguageCode(int $id, string $languageCode): string
    {
        return $this->cacheAdapter->get(
            sprintf('item_description_retriever.descriptions_text.%s.%s', $id, $languageCode),
            function (ItemInterface $item) use ($id, $languageCode) {
                return implode(
                    ' ',
                    array_column(
                        $this->itemDescriptionRepository->getDescriptionsByItemIdAndLaguageCode($languageCode, $id),
                        'description'
                    )
                );
            }
        );
    }
}