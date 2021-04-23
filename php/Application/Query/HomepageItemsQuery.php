<?php declare(strict_types=1);

namespace App\Application\Query;

use App\Common\Shared\Domain\Bus\Query\Query;

class HomepageItemsQuery implements Query
{
    /** @var int */
    private $topDownloadsApplicationsLimit = 20;

    /** @var int */
    private $topDownloadsGamesLimit = 20;

    /** @var int */
    private $bestApksLimit = 20;

    /** @var int */
    private $newAppsLimit = 10;

    /** @var int */
    private $newGamesLimit = 10;

    /** @var int */
    private $bestAppsLimit = 10;

    /** @var int */
    private $bestGamesLimit = 10;

    public function getTopDownloadsApplicationsLimit(): int
    {
        return $this->topDownloadsApplicationsLimit;
    }

    public function setTopDownloadsApplicationsLimit(int $topDownloadsApplicationsLimit): self
    {
        $this->topDownloadsApplicationsLimit = $topDownloadsApplicationsLimit;
        return $this;
    }

    public function getTopDownloadsGamesLimit(): int
    {
        return $this->topDownloadsGamesLimit;
    }

    public function setTopDownloadsGamesLimit(int $topDownloadsGamesLimit): self
    {
        $this->topDownloadsGamesLimit = $topDownloadsGamesLimit;
        return $this;
    }

    public function getBestApksLimit(): int
    {
        return $this->bestApksLimit;
    }

    public function setBestApksLimit(int $bestApksLimit): self
    {
        $this->bestApksLimit = $bestApksLimit;
        return $this;
    }

    public function getNewAppsLimit(): int
    {
        return $this->newAppsLimit;
    }

    public function setNewAppsLimit(int $newAppsLimit): self
    {
        $this->newAppsLimit = $newAppsLimit;
        return $this;
    }

    public function getNewGamesLimit(): int
    {
        return $this->newGamesLimit;
    }

    public function setNewGamesLimit(int $newGamesLimit): self
    {
        $this->newGamesLimit = $newGamesLimit;
        return $this;
    }

    public function getBestAppsLimit(): int
    {
        return $this->bestAppsLimit;
    }

    public function setBestAppsLimit(int $bestAppsLimit): self
    {
        $this->bestAppsLimit = $bestAppsLimit;
        return $this;
    }

    public function getBestGamesLimit(): int
    {
        return $this->bestGamesLimit;
    }

    public function setBestGamesLimit(int $bestGamesLimit): self
    {
        $this->bestGamesLimit = $bestGamesLimit;
        return $this;
    }
}
