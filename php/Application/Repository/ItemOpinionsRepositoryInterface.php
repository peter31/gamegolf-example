<?php declare(strict_types=1);

namespace App\Application\Repository;

use App\Application\ResponseModel\ListResponseModel;
use App\Application\ResponseModel\PaginatedListResponseModel;

interface ItemOpinionsRepositoryInterface
{
    /**
     * Retrieves latest comments for given type and optional category
     *
     * @param string $languageCode
     * @param int $limit
     * @param int $typeId
     * @param int|null $categoryId
     * @return ListResponseModel
     */
    public function getLatest(string $languageCode, int $limit, int $typeId, ?int $categoryId = null): ListResponseModel;

    /**
     * Retrieves latest comments with items for given type and optional category
     *
     * @param string $languageCode
     * @param int $limit
     * @param int $typeId
     * @param int|null $categoryId
     * @return ListResponseModel
     */
    public function getLatestWithItems(string $languageCode, int $limit, int $typeId, ?int $categoryId = null): ListResponseModel;

    /**
     * Get comments by provided item ID with pagination
     *
     * @param string $languageCode
     * @param int $itemId
     * @param int $page
     * @param int $limit
     * @return PaginatedListResponseModel
     */
    public function getByItemId(string $languageCode, int $itemId, int $page, int $limit): PaginatedListResponseModel;
}
