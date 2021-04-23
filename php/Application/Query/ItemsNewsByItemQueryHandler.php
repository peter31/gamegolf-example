<?php declare(strict_types=1);

namespace App\Application\Query;

use App\Application\CachedRetriever\NewsCachedRetrieverInterface;
use App\Common\Shared\Domain\Bus\Query\QueryHandler;

class ItemsNewsByItemQueryHandler implements QueryHandler
{
    /** @var NewsCachedRetrieverInterface */
    private $newsCachedRetriever;

    public function __construct(NewsCachedRetrieverInterface $newsCachedRetriever)
    {
        $this->newsCachedRetriever = $newsCachedRetriever;
    }

    public function __invoke(ItemsNewsByItemQuery $queryModel)
    {
        return $this->newsCachedRetriever->getByItem($queryModel->getLanguageCode(), $queryModel->getItemId(), $queryModel->getLimit());
    }
}
