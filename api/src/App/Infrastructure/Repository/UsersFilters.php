<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Infrastructure\Repository\Filters\Filters;
use App\Infrastructure\Repository\Filters\FiltersInterface;

final class UsersFilters extends Filters implements FiltersInterface
{
    /**
     * @var string
     */
    const ALIAS = 'user.';
    /**
     * @var array
     */
    const POSSIBLE_FILTERS = [
        'admin',
        'status'
    ];

    public function __construct(array $filters)
    {
        $this->filters = self::ALIAS;
        $this->filters = parent::selectFilters($filters, self::POSSIBLE_FILTERS);
    }
}
