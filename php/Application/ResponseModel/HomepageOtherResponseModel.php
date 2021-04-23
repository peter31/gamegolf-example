<?php declare(strict_types=1);

namespace App\Application\ResponseModel;

use App\Common\Shared\Domain\Bus\Query\Response;

class HomepageOtherResponseModel implements Response
{
    /** @var array */
    private $topCollections = [];

    /** @var array */
    private $latestNews = [];

    /** @var array */
    private $latestReviews = [];

    /** @var array */
    private $topExperts = [];

    public function getTopCollections(): array
    {
        return $this->topCollections;
    }

    public function setTopCollections(array $topCollections): self
    {
        $this->topCollections = $topCollections;
        return $this;
    }

    public function getLatestNews(): array
    {
        return $this->latestNews;
    }

    public function setLatestNews(array $latestNews): self
    {
        $this->latestNews = $latestNews;
        return $this;
    }

    public function getLatestReviews(): array
    {
        return $this->latestReviews;
    }

    public function setLatestReviews(array $latestReviews): self
    {
        $this->latestReviews = $latestReviews;
        return $this;
    }

    public function getTopExperts(): array
    {
        return $this->topExperts;
    }

    public function setTopExperts(array $topExperts): self
    {
        $this->topExperts = $topExperts;
        return $this;
    }
}
