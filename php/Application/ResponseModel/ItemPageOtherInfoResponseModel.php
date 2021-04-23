<?php declare(strict_types=1);

namespace App\Application\ResponseModel;

use App\Common\Shared\Domain\Bus\Query\Response;

class ItemPageOtherInfoResponseModel implements Response
{
    /** @var ItemOpinionResponseModel[] */
    private $comments = [];

    /** @var ItemApkResponseModel[] */
    private $apks = [];

    /** @var ItemResponseModel[] */
    private $relatedItems = [];

    /** @var ItemResponseModel[] */
    private $topVotedItems = [];

    /** @var NewsResponseModel[] */
    private $news = [];

    public function getComments(): array
    {
        return $this->comments;
    }

    public function setComments(array $comments): self
    {
        $this->comments = $comments;
        return $this;
    }

    public function getApks(): array
    {
        return $this->apks;
    }

    public function setApks(array $apks): self
    {
        $this->apks = $apks;
        return $this;
    }

    public function getRelatedItems(): array
    {
        return $this->relatedItems;
    }

    public function setRelatedItems(array $relatedItems): self
    {
        $this->relatedItems = $relatedItems;
        return $this;
    }

    public function getTopVotedItems(): array
    {
        return $this->topVotedItems;
    }

    public function setTopVotedItems(array $topVotedItems): self
    {
        $this->topVotedItems = $topVotedItems;
        return $this;
    }

    public function getNews(): array
    {
        return $this->news;
    }

    public function setNews(array $news): self
    {
        $this->news = $news;
        return $this;
    }
}
