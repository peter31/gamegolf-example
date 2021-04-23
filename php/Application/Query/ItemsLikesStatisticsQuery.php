<?php declare(strict_types=1);

namespace App\Application\Query;

use App\Common\Shared\Domain\Bus\Query\Query;

class ItemsLikesStatisticsQuery implements Query
{
    /** @var int */
    private $itemId;

    /** @var string */
    private $languageCode;

    public function getItemId(): ?int
    {
        return $this->itemId;
    }

    public function setItemId(?int $itemId): self
    {
        $this->itemId = $itemId;
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
}
