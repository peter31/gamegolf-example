<?php declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Query\ItemOpinionsByItemQuery;
use App\Application\Query\ItemOpinionsLatestQuery;
use App\Application\ResponseModel\ItemOpinionResponseModel;
use App\Application\ResponseModel\PaginatedListResponseModel;
use App\Common\Shared\Domain\Bus\Query\QueryBus;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations\Items;
use OpenApi\Annotations\Parameter;
use OpenApi\Annotations\Response;
use OpenApi\Annotations\Schema;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ItemOpinionsController
{
    /**
     * @ParamConverter("queryModel", converter="androidlista.request_dto")
     * @Parameter(name="category_id", in="query", description="Term to search")
     * @Parameter(name="type_id", in="query", description="Items type ID")
     * @Parameter(name="limit", in="query", description="Number of rows to return")
     * @Response(response=200, description="List of found comments",
     *     @Schema(
     *         type="array",
     *         @Items(ref=@Model(type=ItemOpinionResponseModel::class, groups={"short"}))
     *     )
     * )
     * @Rest\View(serializerGroups={"Default", "item": {"item_preview"}})
     */
    public function latest(ItemOpinionsLatestQuery $queryModel, string $languageCode, QueryBus $queryBus): array
    {
        return $queryBus->ask($queryModel->setLanguageCode($languageCode))->getData();
    }

    /**
     * @ParamConverter("queryModel", converter="androidlista.request_dto")
     * @Parameter(name="page", in="query", description="Page number")
     * @Parameter(name="limit", in="query", description="Number of rows to return")
     * @Response(response=200, description="List of found items and pagination",
     *     @Schema(ref=@Model(type=PaginatedListResponseModel::class))
     * )
     * @Rest\View()
     */
    public function byItem(ItemOpinionsByItemQuery $queryModel, string $languageCode, int $itemId, QueryBus $queryBus): PaginatedListResponseModel
    {
        return $queryBus->ask($queryModel->setItemId($itemId)->setLanguageCode($languageCode));
    }
}
