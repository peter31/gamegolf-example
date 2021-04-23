<?php declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Query\CollectionsLatestQuery;
use App\Application\ResponseModel\CollectionResponseModel;
use App\Common\Shared\Domain\Bus\Query\QueryBus;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations\Items;
use OpenApi\Annotations\Parameter;
use OpenApi\Annotations\Response;
use OpenApi\Annotations\Schema;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class CollectionsController
{
    /**
     * @ParamConverter("queryModel", converter="androidlista.request_dto")
     * @Parameter(name="limit", in="query", description="Number of collections to retorn")
     * @Parameter(name="items_limit", in="query", description="Number of items per collection to retorn")
     * @Response(response=200, description="List of found items and pagination",
     *     @Schema(
     *         type="array",
     *         @Items(ref=@Model(type=CollectionResponseModel::class))
     *     )
     * )
     * @Rest\View(serializerGroups={"Default", "items": {"item_preview"}})
     */
    public function latest(CollectionsLatestQuery $queryModel, string $languageCode, QueryBus $queryBus): array
    {
        return $queryBus->ask($queryModel->setLanguageCode($languageCode))->getData();
    }
}
