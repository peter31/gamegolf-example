<?php declare(strict_types=1);

namespace App\Application\ResponseTransformer;

use App\Application\ResponseModel\ItemResponseModel;
use Solarium\QueryType\Select\Result\Document;

class ItemsCombinedResponseTransformer implements ItemsResponseTransformerInterface
{
    use ResponseTransformerListTrait;

    /** @var ItemsResponseTransformer */
    private $itemsResponseTransformer;

    /** @var ItemsLinksResponseTransformer */
    private $itemsLinksResponseTransformer;

    /** @var ItemsImagesResponseTransformer */
    private $itemsImagesResponseTransformer;

    /** @var ItemsDescriptionResponseTransformer */
    private $itemsDescriptionResponseTransformer;

    public function __construct(
        ItemsResponseTransformer $itemsResponseTransformer,
        ItemsLinksResponseTransformer $itemsLinksResponseTransformer,
        ItemsImagesResponseTransformer $itemsImagesResponseTransformer,
        ItemsDescriptionResponseTransformer $itemsDescriptionResponseTransformer
    ) {
        $this->itemsResponseTransformer = $itemsResponseTransformer;
        $this->itemsLinksResponseTransformer = $itemsLinksResponseTransformer;
        $this->itemsImagesResponseTransformer = $itemsImagesResponseTransformer;
        $this->itemsDescriptionResponseTransformer = $itemsDescriptionResponseTransformer;
    }

    /**
     * @param Document[] $items
     * @param string $languageCode
     * @return ItemResponseModel[]
     */
    public function transform(array $items, string $languageCode): array
    {
        return $this->itemsImagesResponseTransformer->transform(
            $this->itemsLinksResponseTransformer->transform(
                $this->itemsDescriptionResponseTransformer->transform(
                    $this->itemsResponseTransformer->transform($items, $languageCode),
                    $languageCode
                ),
                $languageCode
            ),
            $languageCode
        );
    }
}
