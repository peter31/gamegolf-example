<?php declare(strict_types=1);

namespace App\Application\Query;

use App\Common\Shared\Domain\Bus\Query\Query;

class HomepageOtherQuery implements Query
{
    /** @var int */
    private $topCollectionsLimit = 5;

    /** @var int */
    private $topCollectionsItemsLimit = 3;

    /** @var int */
    private $latestNewsLimit = 10;

    /** @var int */
    private $latestReviewsLimit = 4;

    /** @var int */
    private $topExpertsLimit = 8;

    public function getTopCollectionsLimit(): int
    {
        return $this->topCollectionsLimit;
    }

    public function setTopCollectionsLimit(int $topCollectionsLimit): self
    {
        $this->topCollectionsLimit = $topCollectionsLimit;
        return $this;
    }

    public function getTopCollectionsItemsLimit(): int
    {
        return $this->topCollectionsItemsLimit;
    }

    public function setTopCollectionsItemsLimit(int $topCollectionsItemsLimit): self
    {
        $this->topCollectionsItemsLimit = $topCollectionsItemsLimit;
        return $this;
    }

    public function getLatestNewsLimit(): int
    {
        return $this->latestNewsLimit;
    }

    public function setLatestNewsLimit(int $latestNewsLimit): self
    {
        $this->latestNewsLimit = $latestNewsLimit;
        return $this;
    }

    public function getLatestReviewsLimit(): int
    {
        return $this->latestReviewsLimit;
    }

    public function setLatestReviewsLimit(int $latestReviewsLimit): self
    {
        $this->latestReviewsLimit = $latestReviewsLimit;
        return $this;
    }

    public function getTopExpertsLimit(): int
    {
        return $this->topExpertsLimit;
    }

    public function setTopExpertsLimit(int $topExpertsLimit): self
    {
        $this->topExpertsLimit = $topExpertsLimit;
        return $this;
    }
}
