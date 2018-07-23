<?php

declare(strict_types=1);

namespace App\Core\Middleware\InputFilter;

use Psr\Container\ContainerInterface;
use App\Middleware\InputFilter\UserInputFilter;

final class InputFilterFactory
{
    public function __invoke(ContainerInterface $container, string $name)
    {
        $filters = $container->get('InputFilterManager');

        return new $name($filters->get($name));
    }
}
