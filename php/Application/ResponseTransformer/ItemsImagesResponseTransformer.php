<?php declare(strict_types=1);

namespace App\Application\ResponseTransformer;

use App\Application\ResponseModel\ItemResponseModel;

class ItemsImagesResponseTransformer implements ItemsModelResponseTransformerInterface
{
    use ResponseTransformerListTrait;

    /**
     * @param ItemResponseModel[] $items
     * @return ItemResponseModel[]
     */
    public function transform(array $items, string $languageCode): array
    {
        return array_map(
            function(ItemResponseModel $itemModel) {
                $itemModel->setMainImage(
                    $this->prepareUrl($itemModel->getMainImage() ?: '/images/no-photo-thumb.png')
                );

                $itemModel->setImages(
                    array_map(
                        function (string $imagePath) {
                            return $this->prepareUrl($imagePath);
                        },
                        $itemModel->getImages()
                    )
                );

                return $itemModel;
            },
            $items
        );
    }

    private function prepareUrl(string $path)
    {
        return sprintf(
            'https://media.%s%s',
            'cdnandroid.com',
            $path
        );
    }
}