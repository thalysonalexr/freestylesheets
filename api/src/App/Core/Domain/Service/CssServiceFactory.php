<?php

declare(strict_types=1);

namespace App\Core\Domain\Service;

use App\Domain\Service\CssService;
use App\Infrastructure\Repository\Css;
use Interop\Container\ContainerInterface;

final class CssServiceFactory
{
    public function __invoke(ContainerInterface $container): CssService
    {
        return new CssService($container->get(Css::class));
    }
}
