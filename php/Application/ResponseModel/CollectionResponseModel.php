<?php declare(strict_types=1);

namespace App\Application\ResponseModel;

use App\Common\Al\Collections\Domain\Collection;

class CollectionResponseModel
{
    /** @var int */
    private $id;
    /** @var string */
    private $name;
    /** @var ?string */
    private $description;
    /** @var string */
    private $language;
    /** @var bool */
    private $isPublished;
    /** @var bool */
    private $isIndexed;
    /** @var \DateTime */
    private $updatedAt;
    /** @var ItemResponseModel[] */
    private $items;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;
        return $this;
    }

    public function isPublished(): bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;
        return $this;
    }

    public function isIndexed(): bool
    {
        return $this->isIndexed;
    }

    public function setIsIndexed(bool $isIndexed): self
    {
        $this->isIndexed = $isIndexed;
        return $this;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function setItems(array $items): self
    {
        $this->items = $items;
        return $this;
    }

    public static function fromDomainCollection(Collection $collection): self
    {
        $item = new self();
        $item->id = $collection->id()->value();
        $item->name = $collection->name()->value();
        $item->description = $collection->description();
        $item->language = $collection->language();
        $item->isPublished = $collection->isPublished();
        $item->isIndexed = $collection->isIndexed();
        $item->updatedAt = $collection->updatedAt();

        return $item;
    }
}
