<?php declare(strict_types=1);

namespace App\Application\ResponseModel;

use App\Common\Shared\Domain\Bus\Query\Response;

class HomepageItemsResponseModel implements Response
{
    /** @var array */
    private $topDownloadsApplications = [];

    /** @var array */
    private $topDownloadsGames = [];

    /** @var array */
    private $bestApks = [];

    /** @var array */
    private $newApps = [];

    /** @var array */
    private $newGames = [];

    /** @var array */
    private $bestApps = [];

    /** @var array */
    private $bestGames = [];

    public function getTopDownloadsApplications(): ?array
    {
        return $this->topDownloadsApplications;
    }

    public function setTopDownloadsApplications(?array $topDownloadsApplications): self
    {
        $this->topDownloadsApplications = $topDownloadsApplications;
        return $this;
    }

    public function getTopDownloadsGames(): ?array
    {
        return $this->topDownloadsGames;
    }

    public function setTopDownloadsGames(?array $topDownloadsGames): self
    {
        $this->topDownloadsGames = $topDownloadsGames;
        return $this;
    }

    public function getBestApks(): ?array
    {
        return $this->bestApks;
    }

    public function setBestApks(?array $bestApks): self
    {
        $this->bestApks = $bestApks;
        return $this;
    }

    public function getNewApps(): ?array
    {
        return $this->newApps;
    }

    public function setNewApps(?array $newApps): self
    {
        $this->newApps = $newApps;
        return $this;
    }

    public function getNewGames(): ?array
    {
        return $this->newGames;
    }

    public function setNewGames(?array $newGames): self
    {
        $this->newGames = $newGames;
        return $this;
    }

    public function getBestApps(): ?array
    {
        return $this->bestApps;
    }

    public function setBestApps(?array $bestApps): self
    {
        $this->bestApps = $bestApps;
        return $this;
    }

    public function getBestGames(): ?array
    {
        return $this->bestGames;
    }

    public function setBestGames(?array $bestGames): self
    {
        $this->bestGames = $bestGames;
        return $this;
    }
}
