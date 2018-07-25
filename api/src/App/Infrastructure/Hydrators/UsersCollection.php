<?php

declare(strict_types=1);

namespace App\Infrastructure\Hydrators;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;

final class UsersCollection extends Paginator implements \IteratorAggregate
{
    public function __construct(array $users)
    {
        $this->adapter = new ArrayAdapter($users);
    }
}
