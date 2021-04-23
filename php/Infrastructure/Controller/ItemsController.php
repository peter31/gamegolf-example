<?php declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Exception\NotFoundException;
use App\Application\Query\ItemDescriptionsByItemQuery;
use App\Application\Query\ItemOpinionsByItemQuery;
use App\Application\Query\ItemPageCommonInfoQuery;
use App\Application\Query\ItemPageOtherInfoQuery;
use App\Application\Query\ItemsApksByItemQuery;
use App\Application\Query\ItemsLikesStatisticsQuery;
use App\Application\Query\ItemsNeighborsQuery;
use App\Application\Query\ItemsNewsByItemQuery;
use App\Application\Query\ItemsPublicByIdQuery;
use App\Application\Query\ItemsRelatedByItemQuery;
use App\Application\Query\ItemsRelevantQuery;
use App\Application\Query\ItemsSearchQuery;
use App\Application\Query\ItemsTopDownloadsQuery;
use App\Application\Query\ItemsTopVotedByItemQuery;
use App\Application\Query\ItemsTopVotedQuery;
use App\Application\ResponseModel\ItemApkResponseModel;
use App\Application\ResponseModel\ItemDescriptionResponseModel;
use App\Application\ResponseModel\ItemLikesStatisticsResponseModel;
use App\Application\ResponseModel\ItemNeighborsResponseModel;
use App\Application\ResponseModel\ItemPageCommonInfoResponseModel;
use App\Application\ResponseModel\ItemPageOtherInfoResponseModel;
use App\Application\ResponseModel\ItemResponseModel;
use App\Application\ResponseModel\NewsResponseModel;
use App\Application\ResponseModel\PaginatedListResponseModel;
use App\Common\Shared\Domain\Bus\Query\QueryBus;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations\Items;
use OpenApi\Annotations\Parameter;
use OpenApi\Annotations\Response;
use OpenApi\Annotations\Schema;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ItemsController
{
    /**
     * @ParamConverter("queryModel", converter="androidlista.request_dto")
     * @Parameter(name="term", in="query", description="Term to search")
     * @Parameter(name="page", in="query", description="Requested page")
     * @Parameter(name="limit", in="query", description="Number of rows to return")
     * @Response(response=200, description="List of found items and pagination",
     *     @Schema(ref=@Model(type=PaginatedListResponseModel::class))
     * )
     * @Rest\View()
     */
    public function search(ItemsSearchQuery $queryModel, string $languageCode, QueryBus $queryBus): PaginatedListResponseModel
    {
        return $queryBus->ask($queryModel->setLanguageCode($languageCode));
    }

    /**
     * @ParamConverter("queryModel", converter="androidlista.request_dto")
     * @Parameter(name="term", in="query", description="Term to search")
     * @Parameter(name="page", in="query", description="Requested page")
     * @Parameter(name="limit", in="query", description="Number of rows to return")
     * @Response(
     *     response=200,
     *     description="List of found items",
     *     @Schema(
     *         type="array",
     *         @Items(ref=@Model(type=ItemResponseModel::class, groups={"item_short"}))
     *     )
     * )
     * @Rest\View(serializerGroups={"item_short"})
     * @return ItemResponseModel[]
     */
    public function shortSearch(ItemsSearchQuery $queryModel, string $languageCode, QueryBus $queryBus): array
    {
        return $queryBus->ask($queryModel->setLanguageCode($languageCode))->getData();
    }

    /**
     * @ParamConverter("queryModel", converter="androidlista.request_dto")
     * @Parameter(name="category", in="query", description="Catgegory ID to filter")
     * @Parameter(name="only_apk", in="query", description="Show only APK flag")
     * @Parameter(name="limit", in="query", description="Number of rows to return")
     * @Response(
     *     response=200,
     *     description="List of found top downloads items",
     *     @Schema(
     *         type="array",
     *         @Items(ref=@Model(type=ItemResponseModel::class, groups={"item_preview"}))
     *     )
     * )
     * @Rest\View(serializerGroups={"item_preview"})
     */
    public function topDownloads(ItemsTopDownloadsQuery $queryModel, string $languageCode, QueryBus $queryBus): array
    {
        return $queryBus->ask($queryModel->setLanguageCode($languageCode))->getData();
    }

    /**
     * @ParamConverter("queryModel", converter="androidlista.request_dto")
     * @Parameter(name="category", in="query", description="Category ID to search in")
     * @Parameter(name="page", in="query", description="Requested page")
     * @Parameter(name="limit", in="query", description="Number of rows to return")
     * @Response(response=200, description="List of relevant items and pagination",
     *     @Schema(ref=@Model(type=PaginatedListResponseModel::class))
     * )
     * @Rest\View(serializerGroups={"Default", "data": {"item_preview"}})
     */
    public function relevant(ItemsRelevantQuery $queryModel, string $languageCode, QueryBus $queryBus): PaginatedListResponseModel
    {
        return $queryBus->ask($queryModel->setLanguageCode($languageCode));
    }

    /**
     * @ParamConverter("queryModel", converter="androidlista.request_dto")
     * @Parameter(name="category", in="query", description="Category ID to search in")
     * @Parameter(name="page", in="query", description="Requested page")
     * @Parameter(name="limit", in="query", description="Number of rows to return")
     * @Response(response=200, description="List of relevant items and pagination",
     *     @Schema(ref=@Model(type=PaginatedListResponseModel::class))
     * )
     * @Rest\View(serializerGroups={"Default", "data": {"item_preview"}})
     */
    public function topVoted(ItemsTopVotedQuery $queryModel, string $languageCode, QueryBus $queryBus): PaginatedListResponseModel
    {
        return $queryBus->ask($queryModel->setLanguageCode($languageCode));
    }

    /**
     * @ParamConverter("queryModel", converter="androidlista.request_dto")
     * @Response(response=200, description="Found item information",
     *     @Schema(ref=@Model(type=ItemResponseModel::class))
     * )
     * @Rest\View()
     */
    public function publicById(ItemsPublicByIdQuery $queryModel, string $languageCode, int $id, QueryBus $queryBus): ?ItemResponseModel
    {
        try {
            return $queryBus->ask($queryModel->setId($id)->setLanguageCode($languageCode));
        } catch (NotFoundException $ex) {
            throw new NotFoundHttpException($ex->getMessage(), $ex);
        }
    }

    /**
     * @ParamConverter("queryModel", converter="androidlista.request_dto")
     * @Response(response=200, description="Found item information",
     *     @Schema(ref=@Model(type=ItemLikesStatisticsResponseModel::class))
     * )
     * @Rest\View()
     */
    public function likesStatistics(ItemsLikesStatisticsQuery $queryModel, string $languageCode, int $id, QueryBus $queryBus): ItemLikesStatisticsResponseModel
    {
        try {
            return $queryBus->ask($queryModel->setItemId($id)->setLanguageCode($languageCode));
        } catch (NotFoundException $ex) {
            throw new NotFoundHttpException($ex->getMessage(), $ex);
        }
    }

    /**
     * @ParamConverter("queryModel", converter="androidlista.request_dto")
     * @Parameter(name="limit", in="query", description="Number of rows to return")
     * @Response(
     *     response=200,
     *     description="List of found APKs for provided item",
     *     @Schema(
     *         type="array",
     *         @Items(ref=@Model(type=ItemApkResponseModel::class))
     *     )
     * )
     * @Rest\View()
     * @return ItemApkResponseModel[]
     */
    public function apksByItem(ItemsApksByItemQuery $queryModel, int $id, QueryBus $queryBus): array
    {
        return $queryBus->ask($queryModel->setItemId($id))->getData();
    }

    /**
     * @ParamConverter("queryModel", converter="androidlista.request_dto")
     * @Response(
     *     response=200,
     *     description="Returns previous and next items in top downloads list",
     *     @Schema(ref=@Model(type=ItemNeighborsResponseModel::class))
     * )
     * @Rest\View()
     * @return ItemApkResponseModel[]
     */
    public function neighborsByItem(ItemsNeighborsQuery $queryModel, int $id, string $languageCode, QueryBus $queryBus): ItemNeighborsResponseModel
    {
        try {
            return $queryBus->ask($queryModel->setItemId($id)->setLanguageCode($languageCode));
        } catch (NotFoundException $ex) {
            throw new NotFoundHttpException($ex->getMessage(), $ex);
        }
    }

    /**
     * @ParamConverter("queryModel", converter="androidlista.request_dto")
     * @Parameter(name="limit", in="query", description="Number of rows to return")
     * @Response(
     *     response=200,
     *     description="List of found related items for provided item",
     *     @Schema(
     *         type="array",
     *         @Items(ref=@Model(type=ItemResponseModel::class))
     *     )
     * )
     * @Rest\View()
     * @return ItemResponseModel[]
     */
    public function relatedByItem(ItemsRelatedByItemQuery $queryModel, int $id, string $languageCode, QueryBus $queryBus): array
    {
        try {
            return $queryBus->ask($queryModel->setItemId($id)->setLanguageCode($languageCode))->getData();
        } catch (NotFoundException $ex) {
            throw new NotFoundHttpException($ex->getMessage(), $ex);
        }
    }

    /**
     * @ParamConverter("queryModel", converter="androidlista.request_dto")
     * @Parameter(name="limit", in="query", description="Number of rows to return")
     * @Response(
     *     response=200,
     *     description="List of found top voted items for provided item",
     *     @Schema(
     *         type="array",
     *         @Items(ref=@Model(type=ItemResponseModel::class))
     *     )
     * )
     * @Rest\View()
     * @return ItemResponseModel[]
     */
    public function topVotedByItem(ItemsTopVotedByItemQuery $queryModel, int $id, string $languageCode, QueryBus $queryBus): array
    {
        try {
            return $queryBus->ask($queryModel->setItemId($id)->setLanguageCode($languageCode))->getData();
        } catch (NotFoundException $ex) {
            throw new NotFoundHttpException($ex->getMessage(), $ex);
        }
    }

    /**
     * @ParamConverter("queryModel", converter="androidlista.request_dto")
     * @Parameter(name="limit", in="query", description="Number of rows to return")
     * @Response(
     *     response=200,
     *     description="List of found news for provided item",
     *     @Schema(
     *         type="array",
     *         @Items(ref=@Model(type=NewsResponseModel::class))
     *     )
     * )
     * @Rest\View()
     * @return NewsResponseModel[]
     */
    public function newsByItem(ItemsNewsByItemQuery $queryModel, int $id, string $languageCode, QueryBus $queryBus): array
    {
        try {
            return $queryBus->ask($queryModel->setItemId($id)->setLanguageCode($languageCode))->getData();
        } catch (NotFoundException $ex) {
            throw new NotFoundHttpException($ex->getMessage(), $ex);
        }
    }

    /**
     * @ParamConverter("queryModel", converter="androidlista.request_dto")
     * @Response(
     *     response=200,
     *     description="List of found desciriptions for provided item",
     *     @Schema(
     *         type="array",
     *         @Items(ref=@Model(type=ItemDescriptionResponseModel::class))
     *     )
     * )
     * @Rest\View()
     * @return ItemDescriptionResponseModel[]
     */
    public function descriptionsByItem(ItemDescriptionsByItemQuery $queryModel, int $id, string $languageCode, QueryBus $queryBus): array
    {
        try {
            return $queryBus->ask($queryModel->setItemId($id)->setLanguageCode($languageCode))->getData();
        } catch (NotFoundException $ex) {
            throw new NotFoundHttpException($ex->getMessage(), $ex);
        }
    }

    /**
     * @ParamConverter("queryModel", converter="androidlista.request_dto")
     * @Response(response=200, description="Item page common info",
     *     @Schema(ref=@Model(type=ItemPageCommonInfoResponseModel::class))
     * )
     * @Rest\View(serializerGroups={"Default", "neighborsItems": {"prev": {"item_preview"}, "next": {"item_preview"}}})
     */
    public function commonInfo(ItemPageCommonInfoQuery $queryModel, string $languageCode, int $id, QueryBus $queryBus): ItemPageCommonInfoResponseModel
    {
        return (new ItemPageCommonInfoResponseModel())
            ->setMainInfo(
                $queryBus->ask(
                    (new ItemsPublicByIdQuery())
                        ->setLanguageCode($languageCode)
                        ->setId($id)
                )
            )
            ->setNeighborsItems(
                $queryBus->ask(
                    (new ItemsNeighborsQuery())
                        ->setLanguageCode($languageCode)
                        ->setItemId($id)
                )
            )
            ->setLikesStatisticsItems(
                $queryBus->ask(
                    (new ItemsLikesStatisticsQuery())
                        ->setLanguageCode($languageCode)
                        ->setItemId($id)
                )
            )
            ->setDescriptions(
                $queryBus->ask(
                    (new ItemDescriptionsByItemQuery())
                        ->setLanguageCode($languageCode)
                        ->setItemId($id)
                )->getData()
            )
            ;
    }

    /**
     * @ParamConverter("queryModel", converter="androidlista.request_dto")
     * @Parameter(name="comments_limit", in="query", description="Number of rows for Comments block")
     * @Parameter(name="apks_limit", in="query", description="Number of rows for APKs block")
     * @Parameter(name="related_items_limit", in="query", description="Number of rows for Related items block")
     * @Parameter(name="top_voted_items_limit", in="query", description="Number of rows for Top Voted items block")
     * @Parameter(name="news_limit", in="query", description="Number of rows for News block")
     * @Response(response=200, description="Item page common info",
     *     @Schema(ref=@Model(type=ItemPageCommonInfoResponseModel::class))
     * )
     * @Rest\View(serializerGroups={"Default", "apks": {"apk_preview"}, "relatedItems": {"item_preview"}, "topVotedItems": {"item_preview"}})
     */
    public function otherInfo(ItemPageOtherInfoQuery $queryModel, string $languageCode, int $id, QueryBus $queryBus): ItemPageOtherInfoResponseModel
    {
        return (new ItemPageOtherInfoResponseModel())
            ->setComments(
                $queryBus->ask(
                    (new ItemOpinionsByItemQuery())
                        ->setLanguageCode($languageCode)
                        ->setItemId($id)
                        ->setLimit($queryModel->getCommentsLimit())
                )->getData()
            )
            ->setApks(
                $queryBus->ask(
                    (new ItemsApksByItemQuery())
                        ->setItemId($id)
                        ->setLimit($queryModel->getApksLimit())
                )->getData()
            )
            ->setRelatedItems(
                $queryBus->ask(
                    (new ItemsRelatedByItemQuery())
                        ->setLanguageCode($languageCode)
                        ->setItemId($id)
                        ->setLimit($queryModel->getRelatedItemsLimit())
                )->getData()
            )
            ->setTopVotedItems(
                $queryBus->ask(
                    (new ItemsTopVotedByItemQuery())
                        ->setLanguageCode($languageCode)
                        ->setItemId($id)
                        ->setLimit($queryModel->getTopVotedItemsLimit())
                )->getData()
            )
            ->setNews(
                $queryBus->ask(
                    (new ItemsNewsByItemQuery())
                        ->setLanguageCode($languageCode)
                        ->setItemId($id)
                        ->setLimit($queryModel->getNewsLimit())
                )->getData()
            )
            ;
    }
}
