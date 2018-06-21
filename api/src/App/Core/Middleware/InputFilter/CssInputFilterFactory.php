<?php

declare(strict_types=1);

namespace App\Core\Middleware\InputFilter;

use Psr\Container\ContainerInterface;
use App\Middleware\InputFilter\CssInputFilter;

final class CssInputFilterFactory
{
    public function __invoke(ContainerInterface $container): CssInputFilter
    {
        $filters = $container->get('InputFilterManager');

        return new CssInputFilter($filters->get(\App\Middleware\InputFilter\CssInputFilter::class));
    }
}
