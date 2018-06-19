<?php

declare(strict_types=1);

namespace App\Handler\Middleware\InputFilter;

use Psr\Container\ContainerInterface;

final class ContactInputFilterFactory
{
    public function __invoke(ContainerInterface $container): ContactInputFilter
    {
        $filters = $container->get('InputFilterManager');

        return new ContactInputFilter($filters->get(\App\Handler\Middleware\InputFilter\ContactInputFilter::class));
    }
}
