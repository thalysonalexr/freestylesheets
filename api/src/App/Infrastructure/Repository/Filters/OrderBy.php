<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Filters;

final class OrderBy
{
    /**
     * @var string
     */
    private $statement;

    public function values(array $fields = null, string $alias = null): self
    {
        if (empty($fields)) {
            $this->statement = '';
        } else {
            $this->statement = implode(', ', array_map(function (string $value) use ($alias) {
                return $alias . $value;
            }, $fields));
        }

        return $this;
    }

    public function order(): string
    {
        if (empty($this->statement)) {
            return '';
        }
        return " ORDER BY {$this->statement}";
    }
}
