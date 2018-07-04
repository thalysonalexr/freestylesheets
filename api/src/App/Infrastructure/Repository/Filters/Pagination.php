<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Filters;

use App\Infrastructure\Repository\Filters\Exception\OffsetRequireLimitException;

final class Pagination
{
    /**
     * @var string
     */
    private $statement;

    public function limit(?string $limit): self
    {
        if (null === $limit) {
            $this->statement = '';
        } else {
            $this->statement .= " LIMIT {$limit} ";
        }

        return $this;
    }

    public function offset(?string $offset): self
    {
        if (null === $offset) {
            $this->statement .= '';
        } else if (empty($this->statement)) {
            throw OffsetRequireLimitException::message();
        } else {
            $this->statement .= "OFFSET {$offset} ";
        }

        return $this;
    }

    public function pagination(): string
    {
        return $this->statement;
    }
}
