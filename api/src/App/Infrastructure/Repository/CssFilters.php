<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

final class CssFilters
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
    /**
     * @var array
     */
    private $cssFilters;
    /**
     * @var string
     */
    private $statements;

    public function __construct(array $filters)
    {
        $this->cssFilters = self::selectFilters($filters, self::POSSIBLE_FILTERS);
        $this->statements = self::selectFilters($filters, self::STATEMENTS);
    }

    public function where(): string
    {
        if (empty($this->cssFilters)) {
            return '';
        }

        $statement = $this->setStatements();

        $where = ' WHERE ' .
            implode(' AND ', array_map(function (string $key) {
                return self::ALIAS . $key . " LIKE :{$key}";
            }, array_keys($this->cssFilters)));

        return $where . $statement;
    }

    public function setStatements(): string
    {
        $order = explode(',', $this->statements['order']?: '');

        $order = empty($order[0]) ? null : $order;

        $query = (new OrderBy)->values($order, self::ALIAS)->order();

        $query .= (new Pagination)->limit(
            $this->statements['limit']
        )->offset(
            $this->statements['offset']
        )->pagination();

        return $query;
    }

    public function data(): array
    {
        return $this->cssFilters;
    }

    public function setLike(): self
    {
        $this->cssFilters = array_map(function (string $value) {
            return '%' . $value . '%';
        }, $this->cssFilters);

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
