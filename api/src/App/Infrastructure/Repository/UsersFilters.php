<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Infrastructure\Repository\Filters\Filters;

final class UsersFilters extends Filters
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

    public function __construct(array $filters = [])
    {
        $this->alias = self::ALIAS;
        $this->filters = parent::selectFilters($filters, self::POSSIBLE_FILTERS);
    }
}
