<?php declare(strict_types=1);

namespace App\Application\Query;

use App\Application\CachedRetriever\ItemsCachedRetrieverInterface;
use App\Common\Shared\Domain\Bus\Query\QueryHandler;

class ItemsNeighborsQueryHandler implements QueryHandler
{
    /** @var ItemsCachedRetrieverInterface */
    private $itemsCachedRetriever;

    public function __construct(ItemsCachedRetrieverInterface $itemsCachedRetriever)
    {
        $this->itemsCachedRetriever = $itemsCachedRetriever;
    }

    public function __invoke(ItemsNeighborsQuery $queryModel)
    {
        return $this->itemsCachedRetriever->getNeighborsByItem(
            $queryModel->getLanguageCode(),
            $queryModel->getItemId()
        );
    }
}
