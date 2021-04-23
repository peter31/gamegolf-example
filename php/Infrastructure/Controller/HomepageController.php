<?php declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Query\CollectionsLatestQuery;
use App\Application\Query\HomepageItemsQuery;
use App\Application\Query\HomepageOtherQuery;
use App\Application\Query\ItemOpinionsLatestQuery;
use App\Application\Query\ItemsRelevantQuery;
use App\Application\Query\ItemsTopDownloadsQuery;
use App\Application\Query\ItemsTopVotedQuery;
use App\Application\Query\NewsLatestQuery;
use App\Application\Query\UsersMostActiveQuery;
use App\Application\ResponseModel\HomepageItemsResponseModel;
use App\Application\ResponseModel\HomepageOtherResponseModel;
use App\Common\Shared\Domain\Bus\Query\QueryBus;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations\Parameter;
use OpenApi\Annotations\Response;
use OpenApi\Annotations\Schema;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class HomepageController
{
    /**
     * @ParamConverter("queryModel", converter="androidlista.request_dto")
     * @Parameter(name="top_downloads_applications_limit", in="query", description="Number of rows for Top Downloads Apps block")
     * @Parameter(name="top_downloads_games_limit", in="query", description="Number of rows for Top Downloads Games block")
     * @Parameter(name="best_apks_limit", in="query", description="Number of rows for Best APKs block")
     * @Parameter(name="new_apps_limit", in="query", description="Number of rows for New Apps block")
     * @Parameter(name="new_games_limit", in="query", description="Number of rows for New Games block")
     * @Parameter(name="best_apps_limit", in="query", description="Number of rows for Best Apps block")
     * @Parameter(name="best_games_limit", in="query", description="Number of rows for Best Games block")
     * @Response(response=200, description="List of items grouped by types",
     *     @Schema(ref=@Model(type=HomepageItemsResponseModel::class))
     * )
     * @Rest\View(serializerGroups={"Default", "topDownloadsApplications": {"item_preview"}, "topDownloadsGames": {"item_preview"}, "bestApks": {"item_preview"}, "newApps": {"item_preview"}, "newGames": {"item_preview"}, "bestApps": {"item_preview"}, "bestGames": {"item_preview"}})
     */
    public function items(HomepageItemsQuery $queryModel, string $languageCode, QueryBus $queryBus): HomepageItemsResponseModel
    {
        return (new HomepageItemsResponseModel())
            ->setTopDownloadsApplications(
                $queryBus->ask(
                    (new ItemsTopDownloadsQuery())
                        ->setLanguageCode($languageCode)
                        ->setCategory(732)
                        ->setLimit($queryModel->getTopDownloadsApplicationsLimit())
                )->getData()
            )
            ->setTopDownloadsGames(
                $queryBus->ask(
                    (new ItemsTopDownloadsQuery())
                        ->setLanguageCode($languageCode)
                        ->setCategory(1)
                        ->setLimit($queryModel->getTopDownloadsGamesLimit())
                )->getData()
            )
            ->setBestApks(
                $queryBus->ask(
                    (new ItemsTopDownloadsQuery())
                        ->setLanguageCode($languageCode)
                        ->setOnlyApk(true)
                        ->setLimit($queryModel->getBestApksLimit())
                )->getData()
            )
            ->setNewApps(
                $queryBus->ask(
                    (new ItemsRelevantQuery())
                        ->setLanguageCode($languageCode)
                        ->setCategory(732)
                        ->setLimit($queryModel->getNewAppsLimit())
                )->getData()
            )
            ->setNewGames(
                $queryBus->ask(
                    (new ItemsRelevantQuery())
                        ->setLanguageCode($languageCode)
                        ->setCategory(1)
                        ->setLimit($queryModel->getNewGamesLimit())
                )->getData()
            )
            ->setBestApps(
                $queryBus->ask(
                    (new ItemsTopVotedQuery())
                        ->setLanguageCode($languageCode)
                        ->setCategory(732)
                        ->setLimit($queryModel->getBestAppsLimit())
                )->getData()
            )
            ->setBestGames(
                $queryBus->ask(
                    (new ItemsTopVotedQuery())
                        ->setLanguageCode($languageCode)
                        ->setCategory(1)
                        ->setLimit($queryModel->getBestGamesLimit())
                )->getData()
            )
        ;
    }

    /**
     * @ParamConverter("queryModel", converter="androidlista.request_dto")
     * @Parameter(name="top_collections_limit", in="query", description="Number of rows in Top Collections block")
     * @Parameter(name="top_collections_items_limit", in="query", description="Number of items per row in Top Collections block")
     * @Parameter(name="latest_news_limit", in="query", description="Number of rows in Top Latest News block")
     * @Parameter(name="latest_reviews_limit", in="query", description="Number of rows in Top Latest Reviews block")
     * @Parameter(name="top_experts_limit", in="query", description="Number of rows in Top Expers block")
     * @Response(response=200, description="List of items grouped by types",
     *     @Schema(ref=@Model(type=HomepageOtherResponseModel::class))
     * )
     * @Rest\View(serializerGroups={"Default", "topCollections": {"items": {"item_preview"}}, "latestReviews": {"item": {"item_preview"}}})
     */
    public function other(HomepageOtherQuery $queryModel, string $languageCode, QueryBus $queryBus): HomepageOtherResponseModel
    {
        return (new HomepageOtherResponseModel())
            ->setTopCollections(
                $queryBus->ask(
                    (new CollectionsLatestQuery())
                        ->setLanguageCode($languageCode)
                        ->setLimit($queryModel->getTopCollectionsLimit())
                        ->setItemsLimit($queryModel->getTopCollectionsItemsLimit())
                )->getData()
            )
            ->setLatestNews(
                $queryBus->ask(
                    (new NewsLatestQuery())
                        ->setLanguageCode($languageCode)
                        ->setLimit($queryModel->getLatestNewsLimit())
                )->getData()
            )
            ->setLatestReviews(
                $queryBus->ask(
                    (new ItemOpinionsLatestQuery())
                        ->setLanguageCode($languageCode)
                        ->setLimit($queryModel->getLatestReviewsLimit())
                )->getData()
            )
            ->setTopExperts(
                $queryBus->ask(
                    (new UsersMostActiveQuery())
                        ->setLanguageCode($languageCode)
                        ->setLimit($queryModel->getTopExpertsLimit())
                )->getData()
            )
        ;
    }
}
