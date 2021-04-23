<?php declare(strict_types=1);

namespace App\Application\CachedRetriever;

use App\Application\ResponseModel\ListResponseModel;

interface NewsCachedRetrieverInterface
{
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
