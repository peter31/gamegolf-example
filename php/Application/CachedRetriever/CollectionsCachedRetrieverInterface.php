<?php declare(strict_types=1);

namespace App\Application\CachedRetriever;

use App\Application\ResponseModel\ListResponseModel;

interface CollectionsCachedRetrieverInterface
{
    /**
     * @param string $type
     * @param string $base
     * @param string $languageCode
     * @param int|null $itemTypeId
     * @return ListResponseModel
     */
    public function getLatestWithItems(string $languageCode, int $limit, int $itemsLimit): ListResponseModel;
}