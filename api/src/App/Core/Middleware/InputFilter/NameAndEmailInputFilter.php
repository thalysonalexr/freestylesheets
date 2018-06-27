<?php

declare(strict_types=1);

namespace App\Core\Middleware\InputFilter;

use Psr\Container\ContainerInterface;
use App\Middleware\InputFilter\NameAndEmailInputFilter;

final class NameAndEmailInputFilterFactory
{
    public function __invoke(ContainerInterface $container): NameAndEmailInputFilter
    {
        $filters = $container->get('InputFilterManager');

        return new NameAndEmailInputFilter($filters->get(\App\Middleware\InputFilter\NameAndEmailInputFilter::class));
    }
}
