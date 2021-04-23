<?php declare(strict_types=1);

namespace App\Application\ResponseModel;

use App\Application\Pagination\PaginationModel;

class ItemsPaginatedListResponseModel extends PaginatedListResponseModel
{
    /** @var PaginationModel */
    protected $pagination;

    /** @var array */
    protected $data;
}
