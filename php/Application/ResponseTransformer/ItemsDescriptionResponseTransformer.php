<?php declare(strict_types=1);

namespace App\Application\ResponseTransformer;

use App\Application\CachedRetriever\SeoDataCachedRetrieverInterface;
use App\Application\Repository\ItemDescriptionRepositoryInterface;
use App\Application\ResponseModel\ItemResponseModel;

class ItemsDescriptionResponseTransformer implements ItemsModelResponseTransformerInterface
{
    use ResponseTransformerListTrait;

    /** @var ItemDescriptionRepositoryInterface */
    private $itemDescriptionRepository;

    /** @var SeoDataCachedRetrieverInterface */
    private $seoDataCachedRetreiver;

    public function __construct(ItemDescriptionRepositoryInterface $itemDescriptionRepository, SeoDataCachedRetrieverInterface $seoDataCachedRetreiver)
    {
        $this->itemDescriptionRepository = $itemDescriptionRepository;
        $this->seoDataCachedRetreiver = $seoDataCachedRetreiver;
    }

    /**
     * @param ItemResponseModel[] $items
     * @param string $languageCode
     * @return ItemResponseModel[]
     */
    public function transform(array $items, string $languageCode): array
    {
        $itemIds = array_map(function (ItemResponseModel $itemModel) {
            return $itemModel->getId();
        }, $items);

        $descriptionsMap = $this->itemDescriptionRepository->getDescriptionsByItemIds($languageCode, $itemIds);
        $sentencesMap = $this->itemDescriptionRepository->getSentencesByItemIds($languageCode, $itemIds);

        return array_map(
            function(ItemResponseModel $itemModel) use ($languageCode, $descriptionsMap, $sentencesMap) {
                if (array_key_exists($itemModel->getId(), $descriptionsMap)) {
                    $itemModel->setDescription($descriptionsMap[$itemModel->getId()]);
                } else {
                    $seoItem = $this->seoDataCachedRetreiver->getByLanguageCodeAndTypes('item', '%item_homepage%', $languageCode, $itemModel->getTypeId());
                    if (null !== $seoItem) {
                        $this->seoDataCachedRetreiver->replaceSeoVarsByItem($seoItem, $itemModel);
                        $itemModel->setDescription($seoItem->getDescriptionTranslate());
                    }
                }

                if (array_key_exists($itemModel->getId(), $sentencesMap)) {
                    $itemModel->setSentence($sentencesMap[$itemModel->getId()]);
                }

                return $itemModel;
            },
            $items
        );
    }
}
