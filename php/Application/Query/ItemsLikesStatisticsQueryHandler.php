<?php declare(strict_types=1);

namespace App\Application\Query;

use App\Application\CachedRetriever\ItemsCachedRetrieverInterface;
use App\Common\Shared\Domain\Bus\Query\QueryHandler;

class ItemsLikesStatisticsQueryHandler implements QueryHandler
{
    /** @var ItemsCachedRetrieverInterface */
    private $itemsCachedRetriever;

    public function __construct(ItemsCachedRetrieverInterface $itemsCachedRetriever)
    {
        $this->itemsCachedRetriever = $itemsCachedRetriever;
    }

    public function __invoke(ItemsLikesStatisticsQuery $queryModel)
    {
        return $this->itemsCachedRetriever->getLikesStatistics(
            $queryModel->getLanguageCode(),
            $queryModel->getItemId()
        );
    }
}
