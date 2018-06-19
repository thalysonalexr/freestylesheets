<?php

declare(strict_types=1);

namespace App\Core\Middleware\InputFilter;

use Psr\Container\ContainerInterface;
use App\Middleware\InputFilter\UserInputFilter;

final class UserInputFilterFactory
{
    public function __invoke(ContainerInterface $container): UserInputFilter
    {
        $filters = $container->get('InputFilterManager');

        return new UserInputFilter($filters->get(\App\Middleware\InputFilter\UserInputFilter::class));
    }
}
