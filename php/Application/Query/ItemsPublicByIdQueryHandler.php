<?php declare(strict_types=1);

namespace App\Application\Query;

use App\Application\CachedRetriever\ItemsCachedRetrieverInterface;
use App\Application\Exception\NotFoundException;
use App\Common\Shared\Domain\Bus\Query\QueryHandler;

class ItemsPublicByIdQueryHandler implements QueryHandler
{
    /** @var ItemsCachedRetrieverInterface */
    private $itemsCachedRetriever;

    public function __construct(ItemsCachedRetrieverInterface $itemsCachedRetriever)
    {
        $this->itemsCachedRetriever = $itemsCachedRetriever;
    }

    public function __invoke(ItemsPublicByIdQuery $queryModel)
    {
        $item = $this->itemsCachedRetriever->getPublicById($queryModel->getLanguageCode(), $queryModel->getId());
        if (null === $item) {
            throw new NotFoundException(sprintf('No item by ID "%s" is found', $queryModel->getId()));
        }

        return $item;
    }
}
