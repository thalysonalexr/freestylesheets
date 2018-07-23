<?php

declare(strict_types=1);

namespace App\Infrastructure\Hydrators;

final class CssCollection implements \IteratorAggregate
{
    /**
     * @var array
     */
    private $css;

    public function __construct(array $css)
    {
        $this->css = $css;
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->css);
    }
}
