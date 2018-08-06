<?php

declare(strict_types=1);

namespace App\Core\Middleware\InputFilter;

use Psr\Container\ContainerInterface;

final class InputManagerFactory
{
    public function __invoke(ContainerInterface $container, string $name)
    {
        return new $name($container->get('InputFilterManager'));
    }
}
