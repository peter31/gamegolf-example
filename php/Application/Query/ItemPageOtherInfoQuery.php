<?php declare(strict_types=1);

namespace App\Application\Query;

use App\Common\Shared\Domain\Bus\Query\Query;

class ItemPageOtherInfoQuery implements Query
{
    /** @var int */
    private $commentsLimit = 10;

    /** @var int */
    private $apksLimit = 10;

    /** @var int */
    private $relatedItemsLimit = 6;

    /** @var int */
    private $topVotedItemsLimit = 6;

    /** @var int */
    private $newsLimit = 5;

    public function getCommentsLimit(): int
    {
        return $this->commentsLimit;
    }

    public function setCommentsLimit(int $commentsLimit): self
    {
        $this->commentsLimit = $commentsLimit;
        return $this;
    }

    public function getApksLimit(): int
    {
        return $this->apksLimit;
    }

    public function setApksLimit(int $apksLimit): self
    {
        $this->apksLimit = $apksLimit;
        return $this;
    }

    public function getRelatedItemsLimit(): int
    {
        return $this->relatedItemsLimit;
    }

    public function setRelatedItemsLimit(int $relatedItemsLimit): self
    {
        $this->relatedItemsLimit = $relatedItemsLimit;
        return $this;
    }

    public function getTopVotedItemsLimit(): int
    {
        return $this->topVotedItemsLimit;
    }

    public function setTopVotedItemsLimit(int $topVotedItemsLimit): self
    {
        $this->topVotedItemsLimit = $topVotedItemsLimit;
        return $this;
    }

    public function getNewsLimit(): int
    {
        return $this->newsLimit;
    }

    public function setNewsLimit(int $newsLimit): self
    {
        $this->newsLimit = $newsLimit;
        return $this;
    }
}
