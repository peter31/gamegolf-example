<?php declare(strict_types=1);

namespace App\Application\Pagination;

class PaginationModel
{
    /** @var int */
    private $limit;

    /** @var int */
    private $offset;

    /** @var int */
    private $totalRows;

    /** @var int */
    private $current;

    /** @var int */
    private $previous;

    /** @var int */
    private $next;

    /** @var int */
    private $last;

    /** @var array */
    private $pages = [];

    public function getLimit(): ?int
    {
        return $this->limit;
    }
    public function setLimit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function getOffset(): ?int
    {
        return $this->offset;
    }

    public function setOffset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    public function getTotalRows(): ?int
    {
        return $this->totalRows;
    }

    public function setTotalRows(int $totalRows): self
    {
        $this->totalRows = $totalRows;
        return $this;
    }

    public function getCurrent(): ?int
    {
        return $this->current;
    }

    public function setCurrent(int $current): self
    {
        $this->current = $current;
        return $this;
    }

    public function getPrevious(): ?int
    {
        return $this->previous;
    }

    public function setPrevious(int $previous): self
    {
        $this->previous = $previous;
        return $this;
    }

    public function getNext(): ?int
    {
        return $this->next;
    }

    public function setNext(int $next): self
    {
        $this->next = $next;
        return $this;
    }

    public function getLast(): ?int
    {
        return $this->last;
    }

    public function setLast(int $last): self
    {
        $this->last = $last;
        return $this;
    }

    public function getPages(): array
    {
        return $this->pages;
    }

    public function setPages(array $pages): self
    {
        $this->pages = $pages;
        return $this;
    }
}
