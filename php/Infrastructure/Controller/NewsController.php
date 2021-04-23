<?php declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Query\NewsLatestQuery;
use App\Application\ResponseModel\NewsResponseModel;
use App\Common\Shared\Domain\Bus\Query\QueryBus;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations\Items;
use OpenApi\Annotations\Parameter;
use OpenApi\Annotations\Response;
use OpenApi\Annotations\Schema;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class NewsController
{
    /**
     * @ParamConverter("queryModel", converter="androidlista.request_dto")
     * @Parameter(name="limit", in="query", description="Number of news to get")
     * @Response(response=200, description="List of found items and pagination",
     *     @Schema(
     *         type="array",
     *         @Items(ref=@Model(type=NewsResponseModel::class))
     *     )
     * )
     * @Rest\View()
     * @return NewsResponseModel[]
     */
    public function latest(NewsLatestQuery $queryModel, string $languageCode, QueryBus $queryBus): array
    {
        return $queryBus->ask($queryModel->setLanguageCode($languageCode))->getData();
    }
}
