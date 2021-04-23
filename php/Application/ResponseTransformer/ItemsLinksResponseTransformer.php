<?php declare(strict_types=1);

namespace App\Application\ResponseTransformer;

use App\Application\CachedRetriever\ProjectDomainCachedRetrieverInterface;
use App\Application\ResponseModel\ItemResponseModel;

class ItemsLinksResponseTransformer implements ItemsModelResponseTransformerInterface
{
    use ResponseTransformerListTrait;

    /** @var ProjectDomainCachedRetrieverInterface */
    private $projectDomainCachedRetriever;

    public function __construct(ProjectDomainCachedRetrieverInterface $projectDomainCachedRetriever)
    {
        $this->projectDomainCachedRetriever = $projectDomainCachedRetriever;
    }

    /**
     * @param ItemResponseModel[] $items
     * @return ItemResponseModel[]
     */
    public function transform(array $items, string $languageCode): array
    {
        $baseUrl = $this->projectDomainCachedRetriever->getBaseUrlByLanguageCode($languageCode);

        return array_map(
            function(ItemResponseModel $itemModel) use ($baseUrl) {
                $itemModel->setLink(
                    sprintf(
                        '%s/item/android-apps/%s/%s/',
                        $baseUrl,
                        $itemModel->getId(),
                        $itemModel->getNameDirify()
                    )
                );
                $itemModel->setQrCode(
                    sprintf('http://chart.apis.google.com/chart?cht=qr&chs=350x350&chld=Q|0&chl=%sn/qr-code/', $itemModel->getLink())
                );

                return $itemModel;
            },
            $items
        );
    }
}
