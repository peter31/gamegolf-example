<?php declare(strict_types=1);

namespace App\Application\ResponseModel;

use App\Common\Shared\Domain\Bus\Query\Response;

class ItemLikesStatisticsResponseModel implements Response
{
    /** @var int */
    private $good = 0;

    /** @var int */
    private $bad = 0;

    /** @var int */
    private $indiference = 0;

    public function getGood(): int
    {
        return $this->good;
    }

    public function setGood(int $good): self
    {
        $this->good = $good;
        return $this;
    }

    public function getBad(): int
    {
        return $this->bad;
    }

    public function setBad(int $bad): self
    {
        $this->bad = $bad;
        return $this;
    }

    public function getIndiference(): int
    {
        return $this->indiference;
    }

    public function setIndiference(int $indiference): self
    {
        $this->indiference = $indiference;
        return $this;
    }

    public function setByType(?int $type, int $value)
    {
        if (null !== $type) {
            switch ($type) {
                case 1:
                    $this->setGood($value);
                    break;
                case 2:
                    $this->setBad($value);
                    break;
                case 3:
                    $this->setIndiference($value);
                    break;
                default:
                    throw new \LogicException(sprintf('Likes type "%s" is not defined', $type));
            }
        }

        return $this;
    }
}
