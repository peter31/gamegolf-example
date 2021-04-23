<?php declare(strict_types=1);

namespace App\Application\Repository;

use App\Application\ResponseModel\ListResponseModel;

interface CollectionsRepositoryInterface
{
    /**
     * Gets latest updated collections
     *
     * @param string $languageCode
     * @param int $limit
     * @return ListResponseModel
     */
    public function getLatest(string $languageCode, int $limit): ListResponseModel;

    /**
     * Gets latest updated collections with items attached
     *
     * @param string $languageCode
     * @param int $limit
     * @param int $itemsLimit
     * @return ListResponseModel
     */
    public function getLatestWithItems(string $languageCode, int $limit, int $itemsLimit): ListResponseModel;
}
