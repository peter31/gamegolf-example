<?php declare(strict_types=1);

namespace App\Application\Repository;

use App\Application\ResponseModel\ListResponseModel;
use App\Application\ResponseModel\NewsResponseModel;

interface NewsRepositoryInterface
{
    /**
     * Get news post info by ID and language
     *
     * @param string $languageCode
     * @param int $id
     * @return NewsResponseModel|null
     */
    public function getById(string $languageCode, int $id): ?NewsResponseModel;

    /**
     * Retrieves latest news list
     *
     * @param string $languageCode
     * @param int $limit
     * @param int|null $categoryId
     * @return ListResponseModel
     */
    public function getLatest(string $languageCode, int $limit, ?int $categoryId = null): ListResponseModel;

    /**
     * Retrieves news by provided item ID
     *
     * @param string $languageCode
     * @param int $itemId
     * @param int $limit
     * @return ListResponseModel
     */
    public function getByItem(string $languageCode, int $itemId, int $limit): ListResponseModel;
}
