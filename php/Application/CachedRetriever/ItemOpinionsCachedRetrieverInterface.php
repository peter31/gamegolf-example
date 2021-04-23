<?php declare(strict_types=1);

namespace App\Application\CachedRetriever;

use App\Application\ResponseModel\ListResponseModel;
use App\Application\ResponseModel\PaginatedListResponseModel;

interface ItemOpinionsCachedRetrieverInterface
{
    /**
     * Get latest comments with related items models by given type ID
     *
     * @param string $languageCode
     * @param int $limit
     * @param int $typeId
     * @param int|null $categoryId
     * @return ListResponseModel
     */
    public function getLatestWithItems(string $languageCode, int $limit, int $typeId, ?int $categoryId = null): ListResponseModel;

    /**
     * Get paginated comments by provided item ID
     *
     * @param string $languageCode
     * @param int $itemId
     * @param int $page
     * @param int $limit
     * @return ListResponseModel
     */
    public function getByItemId(string $languageCode, int $itemId, int $page, int $limit): PaginatedListResponseModel;
}