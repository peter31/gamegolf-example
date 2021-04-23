<?php declare(strict_types=1);

namespace App\Application\ResponseModel;

use App\Common\Al\Items\Domain\Item;
use App\Common\Shared\Domain\Bus\Query\Response;

class ItemApkResponseModel implements Response
{
    /** @var int */
    private $id;

    /** @var int */
    private $itemId;

    /** @var string */
    private $apk;

    /** @var string */
    private $externalUrl;

    /** @var string */
    private $version;

    /** @var string */
    private $size;

    /** @var string */
    private $signature;

    /** @var string */
    private $sha1;

    /** @var bool */
    private $isProcessed;

    /** @var bool */
    private $isDangerous;

    /** @var \Datetime */
    private $versionDate;

    /** @var ItemApkDataResponseModel */
    private $data;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getItemId(): ?int
    {
        return $this->itemId;
    }

    public function setItemId(?int $itemId): self
    {
        $this->itemId = $itemId;
        return $this;
    }

    public function getApk(): ?string
    {
        return $this->apk;
    }

    public function setApk(?string $apk): self
    {
        $this->apk = $apk;
        return $this;
    }

    public function getExternalUrl(): ?string
    {
        return $this->externalUrl;
    }

    public function setExternalUrl(?string $externalUrl): self
    {
        $this->externalUrl = $externalUrl;
        return $this;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(?string $version): self
    {
        $this->version = $version;
        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(?string $size): self
    {
        $this->size = $size;
        return $this;
    }

    public function getSignature(): ?string
    {
        return $this->signature;
    }

    public function setSignature(?string $signature): self
    {
        $this->signature = $signature;
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

    public function isProcessed(): ?bool
    {
        return $this->isProcessed;
    }

    public function setIsProcessed(?bool $isProcessed): self
    {
        $this->isProcessed = $isProcessed;
        return $this;
    }

    public function isDangerous(): ?bool
    {
        return $this->isDangerous;
    }

    public function setIsDangerous(?bool $isDangerous): self
    {
        $this->isDangerous = $isDangerous;
        return $this;
    }

    public function getVersionDate(): ?\Datetime
    {
        return $this->versionDate;
    }

    public function setVersionDate(?\Datetime $versionDate): self
    {
        $this->versionDate = $versionDate;
        return $this;
    }

    public function getData(): ItemApkDataResponseModel
    {
        return $this->data;
    }

    public function setData(ItemApkDataResponseModel $data): self
    {
        $this->data = $data;
        return $this;
    }
}
