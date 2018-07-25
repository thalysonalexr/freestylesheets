<?php

declare(strict_types=1);

namespace App\Infrastructure\Hydrators;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;

final class CssCollection extends Paginator implements \IteratorAggregate
{
    public function __construct(array $css)
    {
        $this->adapter = new ArrayAdapter($css);
    }
}
