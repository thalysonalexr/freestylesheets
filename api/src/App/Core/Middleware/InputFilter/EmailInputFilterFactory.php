<?php

declare(strict_types=1);

namespace App\Core\Middleware\InputFilter;

use Interop\Container\ContainerInterface;
use App\Middleware\InputFilter\EmailInputFilter;

final class EmailInputFilterFactory
{
    public function __invoke(ContainerInterface $container): EmailInputFilter
    {
        $filters = $container->get('InputFilterManager');

        return new EmailInputFilter($filters->get(\App\Middleware\InputFilter\EmailInputFilter::class));
    }
}
