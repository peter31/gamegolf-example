<?php declare(strict_types=1);

namespace App\Application\ResponseModel;

use App\Common\Al\Items\Domain\ItemOpinion;
use App\Common\Shared\Domain\Bus\Query\Response;

class ItemOpinionResponseModel implements Response
{
    /** @var int */
    protected $id;

    /** @var int */
    private $itemId;

    /** @var int */
    private $itemTypeId;

    /** @var string */
    private $title;

    /** @var string */
    private $content;

    /** @var int */
    private $userId;

    /** @var int */
    private $parentId;

    /** @var int */
    private $rating;

    /** @var int */
    private $ratingId;

    /** @var int */
    private $type;

    /** @var bool */
    private $isValidated;

    /** @var bool */
    private $isDeleted = false;

    /** @var string */
    private $languageCode;

    /** @var int */
    private $nrAnswers;

    /** @var string */
    private $username;

    /** @var string */
    private $firstName;

    /** @var string */
    private $lastName;

    /** @var bool */
    private $emailAlert = false;

    /** @var \DateTime */
    private $createdAt;

    /** @var \DateTime */
    private $updatedAt;

    /** @var ItemResponseModel */
    private $item;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getItemId(): int
    {
        return $this->itemId;
    }

    public function setItemId(int $itemId): self
    {
        $this->itemId = $itemId;
        return $this;
    }

    public function getItemTypeId(): ?int
    {
        return $this->itemTypeId;
    }

    public function setItemTypeId(?int $itemTypeId): self
    {
        $this->itemTypeId = $itemTypeId;
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    public function setParentId(?int $parentId): self
    {
        $this->parentId = $parentId;
        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(?int $rating): self
    {
        $this->rating = $rating;
        return $this;
    }

    public function getRatingId(): ?int
    {
        return $this->ratingId;
    }

    public function setRatingId(?int $ratingId): self
    {
        $this->ratingId = $ratingId;
        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(?int $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function isValidated(): ?bool
    {
        return $this->isValidated;
    }

    public function setIsValidated(?bool $isValidated): self
    {
        $this->isValidated = $isValidated;
        return $this;
    }

    public function isDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(?bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;
        return $this;
    }

    public function getLanguageCode(): ?string
    {
        return $this->languageCode;
    }

    public function setLanguageCode(?string $languageCode): self
    {
        $this->languageCode = $languageCode;
        return $this;
    }

    public function getNrAnswers(): ?int
    {
        return $this->nrAnswers;
    }

    public function setNrAnswers(?int $nrAnswers): self
    {
        $this->nrAnswers = $nrAnswers;
        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function isEmailAlert(): ?bool
    {
        return $this->emailAlert;
    }

    public function setEmailAlert(?bool $emailAlert): self
    {
        $this->emailAlert = $emailAlert;
        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getItem(): ?ItemResponseModel
    {
        return $this->item;
    }

    public function setItem(?ItemResponseModel $item): self
    {
        $this->item = $item;
        return $this;
    }

    public static function fromDomainModel(ItemOpinion $itemOpinion)
    {
        $model = new self();
        $model->id = $itemOpinion->getId();
        $model->itemId = $itemOpinion->getItem()->id()->value();
        $model->itemTypeId = $itemOpinion->getItemTypeId();
        $model->title = $itemOpinion->getTitle();
        $model->content = $itemOpinion->getContent();
        $model->userId = $itemOpinion->getUserId();
        $model->rating = $itemOpinion->getRating();
        $model->ratingId = $itemOpinion->getRatingId();
        $model->type = $itemOpinion->getType();
        $model->isValidated = $itemOpinion->isValidated();
        $model->isDeleted = $itemOpinion->isDeleted();
        $model->languageCode = $itemOpinion->getLanguageCode();
        $model->nrAnswers = $itemOpinion->getNrAnswers();
        $model->username = $itemOpinion->getUsername();
        $model->emailAlert = $itemOpinion->isEmailAlert();
        $model->createdAt = $itemOpinion->getCreatedAt();
        $model->updatedAt = $itemOpinion->getUpdatedAt();

        return $model;
    }
}
