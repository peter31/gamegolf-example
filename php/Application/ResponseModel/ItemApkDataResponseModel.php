<?php declare(strict_types=1);

namespace App\Application\ResponseModel;

use App\Common\Shared\Domain\Bus\Query\Response;

class ItemApkDataResponseModel implements Response
{
    /** @var string */
    private $scanId;

    /** @var string */
    private $resource;

    /** @var int */
    private $responseCode;

    /** @var int */
    private $total;

    /** @var int */
    private $positives;

    /** @var \DateTime */
    private $scanDate;

    /** @var string */
    private $permalink;

    /** @var string */
    private $verboseMsg;

    /** @var string */
    private $sha1;

    /** @var string */
    private $sha256;

    /** @var string */
    private $md5;

    /** @var array */
    private $scans;

    public function getScanId(): ?string
    {
        return $this->scanId;
    }

    public function setScanId(?string $scanId): self
    {
        $this->scanId = $scanId;
        return $this;
    }

    public function getResource(): ?string
    {
        return $this->resource;
    }

    public function setResource(?string $resource): self
    {
        $this->resource = $resource;
        return $this;
    }

    public function getResponseCode(): ?int
    {
        return $this->responseCode;
    }

    public function setResponseCode(?int $responseCode): self
    {
        $this->responseCode = $responseCode;
        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(?int $total): self
    {
        $this->total = $total;
        return $this;
    }

    public function getPositives(): ?int
    {
        return $this->positives;
    }

    public function setPositives(?int $positives): self
    {
        $this->positives = $positives;
        return $this;
    }

    public function getScanDate(): ?\DateTime
    {
        return $this->scanDate;
    }

    public function setScanDate(?\DateTime $scanDate): self
    {
        $this->scanDate = $scanDate;
        return $this;
    }

    public function getPermalink(): ?string
    {
        return $this->permalink;
    }

    public function setPermalink(?string $permalink): self
    {
        $this->permalink = $permalink;
        return $this;
    }

    public function getVerboseMsg(): ?string
    {
        return $this->verboseMsg;
    }

    public function setVerboseMsg(?string $verboseMsg): self
    {
        $this->verboseMsg = $verboseMsg;
        return $this;
    }

    public function getSha1(): ?string
    {
        return $this->sha1;
    }

    public function setSha1(?string $sha1): self
    {
        $this->sha1 = $sha1;
        return $this;
    }

    public function getSha256(): ?string
    {
        return $this->sha256;
    }

    public function setSha256(?string $sha256): self
    {
        $this->sha256 = $sha256;
        return $this;
    }

    public function getMd5(): ?string
    {
        return $this->md5;
    }

    public function setMd5(?string $md5): self
    {
        $this->md5 = $md5;
        return $this;
    }

    public function getScans(): ?array
    {
        return $this->scans;
    }

    public function setScans(?array $scans): self
    {
        $this->scans = $scans;
        return $this;
    }
}
