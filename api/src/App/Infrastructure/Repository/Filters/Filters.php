<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Filters;

use App\Infrastructure\Repository\Filters\OrderBy;
use App\Infrastructure\Repository\Filters\Pagination;

use function implode;
use function array_map;
use function array_keys;
use function explode;
use function array_filter;
use function in_array;

class Filters implements FiltersInterface
{
    /**
     * @var array
     */
    protected $filters;
    /**
     * @var string
     */
    protected $statements;
    /**
     * @var string
     */
    protected $alias;

    public function where(bool $like = true): string
    {
        if (empty($this->filters) && empty($this->statements)) {
            return '';
        }

        $statement = $this->setStatements();
        $equals = $like ? "LIKE" : "=";

        $where = implode(' AND ', array_map(function (string $key) use ($equals) {
            return $this->alias . $key . " {$equals} :{$key}";
        }, array_keys($this->filters)));

        $where = ! empty($where) ? ' WHERE ' . $where : '';

        return $where . $statement;
    }

    public function setStatements(): string
    {
        $order = explode(',', $this->statements['order']?: '');

        $order = empty($order[0]) ? null : $order;

        $query = (new OrderBy)->values($order, $this->alias)->order();

        $query .= (new Pagination)->limit(
            $this->statements['limit']
        )->offset(
            $this->statements['offset']
        )->pagination();

        return $query;
    }

    public function data(): ?array
    {
        return $this->filters;
    }

    public function setLike(): self
    {
        $this->filters = array_map(function (string $value) {
            return '%' . $value . '%';
        }, $this->filters ?: []);

        return $this;
    }

    public static function selectFilters(array $filters, array $possibles): array
    {
        return array_filter(
            $filters,
            function (string $key) use ($possibles) {
                return in_array($key, $possibles);
            }, ARRAY_FILTER_USE_KEY
        );
    }
}
