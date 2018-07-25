<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Infrastructure\Repository\Filters\Filters;

final class CssFilters extends Filters
{
    /**
     * @var string
     */
    const ALIAS = 'style.';
    /**
     * @var array
     */
    const POSSIBLE_FILTERS = [
        'id',
        'name'
    ];
    /**
     * @var array
     */
    const STATEMENTS = [
        'limit',
        'offset',
        'order'
    ];

    public function __construct(array $filters = [])
    {
        $this->alias = self::ALIAS;
        $this->filters = parent::selectFilters($filters, self::POSSIBLE_FILTERS);
        $this->statements = parent::selectFilters($filters, self::STATEMENTS);
    }
}
