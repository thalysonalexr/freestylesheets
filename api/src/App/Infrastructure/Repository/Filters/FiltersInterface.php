<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Filters;

interface FiltersInterface
{
    public function __construct(array $filters);

    public function where(bool $like = true): string;
}
