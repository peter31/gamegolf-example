<?php declare(strict_types=1);

namespace App\Application\Query;

use App\Application\CachedRetriever\CollectionsCachedRetrieverInterface;
use App\Application\ResponseModel\ListResponseModel;
use App\Common\Shared\Domain\Bus\Query\QueryHandler;

class CollectionsLatestQueryHandler implements QueryHandler
{
    /** @var CollectionsCachedRetrieverInterface */
    private $collectionsCachedRetriever;

    public function __construct(CollectionsCachedRetrieverInterface $collectionsCachedRetriever)
    {
        $this->collectionsCachedRetriever = $collectionsCachedRetriever;
    }

    public function __invoke(CollectionsLatestQuery $queryModel)
    {
        return $this->collectionsCachedRetriever->getLatestWithItems(
            $queryModel->getLanguageCode(),
            $queryModel->getLimit(),
            $queryModel->getItemsLimit()
        );
    }
}
