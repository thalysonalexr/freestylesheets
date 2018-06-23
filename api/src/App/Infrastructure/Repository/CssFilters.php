<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

final class CssFilters
{
    /**
     * @var string
     */
    const ALIAS = 'style';
    /**
     * @var array
     */
    private $cssFilters;

    public function __construct(array $filters)
    {
        $possible_filters = [
            'id',
            'name'
        ];

        $this->cssFilters = array_filter(
            $filters,
            function (string $key) use ($possible_filters) {
                return in_array($key, $possible_filters);
            }, ARRAY_FILTER_USE_KEY
        );
    }

    public function where(): string
    {
        if (empty($this->cssFilters)) {
            return '';
        }

        return ' WHERE ' .
            implode(' AND ', array_map(function (string $key) {
                return self::ALIAS . '.' . $key . " LIKE :{$key}";
            }, array_keys($this->cssFilters)));
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
}
