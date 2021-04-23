<?php declare(strict_types=1);

namespace App\Application\ResponseModel;

use App\Common\Shared\Domain\Bus\Query\Response;

class ItemNeighborsResponseModel implements Response
{
    /** @var ItemResponseModel|null */
    private $prev;

    /** @var ItemResponseModel|null */
    private $next;

    public function __construct(?ItemResponseModel $prev, ?ItemResponseModel $next)
    {
        $this->prev = $prev;
        $this->next = $next;
    }

    public function getPrev(): ?ItemResponseModel
    {
        return $this->prev;
    }

    public function getNext(): ?ItemResponseModel
    {
        return $this->next;
    }
}
