<?php declare(strict_types=1);

namespace App\Application\Query;

use App\Common\Shared\Domain\Bus\Query\Query;

class CollectionsLatestQuery implements Query
{
    /** @var int */
    private $limit = 5;

    /** @var int */
    private $itemsLimit = 3;

    /** @var string */
    private $languageCode;

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function setLimit(?int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function getItemsLimit(): int
    {
        return $this->itemsLimit;
    }

    public function setItemsLimit(int $itemsLimit): self
    {
        $this->itemsLimit = $itemsLimit;
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
