<?php declare(strict_types=1);

namespace App\Application\ResponseModel;

use App\Common\Shared\Domain\Bus\Query\Response;

class ItemPageCommonInfoResponseModel implements Response
{
    /** @var ItemResponseModel */
    private $mainInfo;

    /** @var ItemNeighborsResponseModel */
    private $neighborsItems;

    /** @var ItemLikesStatisticsResponseModel */
    private $likesStatisticsItems;

    /** @var ItemDescriptionResponseModel[] */
    private $descriptions = [];

    public function getMainInfo(): ItemResponseModel
    {
        return $this->mainInfo;
    }

    public function setMainInfo(ItemResponseModel $mainInfo): self
    {
        $this->mainInfo = $mainInfo;
        return $this;
    }

    public function getNeighborsItems(): ItemNeighborsResponseModel
    {
        return $this->neighborsItems;
    }

    public function setNeighborsItems(ItemNeighborsResponseModel $neighborsItems): self
    {
        $this->neighborsItems = $neighborsItems;
        return $this;
    }

    public function getLikesStatisticsItems(): ItemLikesStatisticsResponseModel
    {
        return $this->likesStatisticsItems;
    }

    public function setLikesStatisticsItems(ItemLikesStatisticsResponseModel $likesStatisticsItems): self
    {
        $this->likesStatisticsItems = $likesStatisticsItems;
        return $this;
    }

    public function getDescriptions(): array
    {
        return $this->descriptions;
    }

    public function setDescriptions(array $descriptions): self
    {
        $this->descriptions = $descriptions;
        return $this;
    }
}
