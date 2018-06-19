<?php

declare(strict_types=1);

namespace App\Core\Middleware\InputFilter;

use Psr\Container\ContainerInterface;
use App\Middleware\InputFilter\LoginInputFilter;

final class LoginInputFilterFactory
{
    public function __invoke(ContainerInterface $container): LoginInputFilter
    {
        $filters = $container->get('InputFilterManager');

        return new LoginInputFilter($filters->get(\App\Middleware\InputFilter\LoginInputFilter::class));
    }
}
