<?php declare(strict_types=1);

namespace App\Application\CachedRetriever;

use App\Application\ResponseModel\ItemLikesStatisticsResponseModel;
use App\Application\ResponseModel\ItemNeighborsResponseModel;
use App\Application\ResponseModel\ItemResponseModel;
use App\Application\ResponseModel\ListResponseModel;
use App\Application\ResponseModel\PaginatedListResponseModel;

interface ItemsCachedRetrieverInterface
{
    /**
     * Get top downloadable items by optional category and only APK status
     *
     * @param string $languageCode
     * @param int|null $category
     * @param bool $onlyApk
     * @param int $limit
     * @return ListResponseModel
     */
    public function getTopDownloadableItems(string $languageCode, ?int $category = null, bool $onlyApk = false, int $limit = 20): ListResponseModel;


    /**
     * Retrieves most relevant items by category with pagination
     *
     * @param string $languageCode
     * @param int|null $categoryId
     * @param int $page
     * @param int|null $limit
     * @return PaginatedListResponseModel
     */
    public function getRelevant(string $languageCode, ?int $categoryId = null, int $page = 1, ?int $limit = 10): PaginatedListResponseModel;

    /**
     * Retrieves top votes list of items with pagination
     *
     * @param string $languageCode
     * @param int|null $categoryId
     * @param int $page
     * @param int|null $limit
     * @return PaginatedListResponseModel
     */
    public function getTopVoted(string $languageCode, ?int $categoryId = null, int $page = 1, ?int $limit = 10): PaginatedListResponseModel;

    /**
     * Gets public item by its ID
     *
     * @param string $languageCode
     * @param int $id
     * @return ItemResponseModel|null
     */
    public function getPublicById(string $languageCode, int $id): ?ItemResponseModel;

    /**
     * Gets statistics about likes grouped by reaction type
     *
     * @param string $languageCode
     * @param int $itemId
     * @return ItemLikesStatisticsResponseModel
     */
    public function getLikesStatistics(string $languageCode, int $itemId): ItemLikesStatisticsResponseModel;

    /**
     * Retrieves list of APKs related to an item with limited rows number if provided
     *
     * @param int $itemId
     * @param int|null $limit
     * @return ListResponseModel
     */
    public function getApksByItem(int $itemId, ?int $limit = null): ListResponseModel;

    /**
     * Retrieves item neighbors considering its categories
     *
     * @param string $languageCode
     * @param int $itemId
     * @return ItemNeighborsResponseModel
     */
    public function getNeighborsByItem(string $languageCode, int $itemId): ItemNeighborsResponseModel;

    /**
     * Retrieves related items ot a provided one
     *
     * @param string $languageCode
     * @param int $itemId
     * @param int $limit
     * @return ListResponseModel
     */
    public function getRelatedByItem(string $languageCode, int $itemId, int $limit): ListResponseModel;

    /**
     * Retrieves top voted items related to a provided one
     *
     * @param string $languageCode
     * @param int $itemId
     * @param int $limit
     * @return ListResponseModel
     */
    public function getTopVotedByItem(string $languageCode, int $itemId, int $limit): ListResponseModel;

    /**
     * Retrieves item descriptions for provided item
     *
     * @param string $languageCode
     * @param int $itemId
     * @return ListResponseModel
     */
    public function getDescriptionsByItem(string $languageCode, int $itemId): ListResponseModel;
}
