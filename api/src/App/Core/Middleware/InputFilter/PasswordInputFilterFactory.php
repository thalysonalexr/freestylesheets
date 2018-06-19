<?php

declare(strict_types=1);

namespace App\Core\Middleware\InputFilter;

use Psr\Container\ContainerInterface;
use App\Middleware\InputFilter\PasswordInputFilter;

final class PasswordInputFilterFactory
{
    public function __invoke(ContainerInterface $container): PasswordInputFilter
    {
        $filters = $container->get('InputFilterManager');

        return new PasswordInputFilter($filters->get(\App\Middleware\InputFilter\PasswordInputFilter::class));
    }
}
