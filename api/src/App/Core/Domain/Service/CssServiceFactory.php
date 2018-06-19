<?php

declare(strict_types=1);

namespace App\Core\Domain\Service;

use App\Domain\Service\CssService;
use App\Infrastructure\Repository\Css;
use Psr\Container\ContainerInterface;

final class CssServiceFactory
{
    public function __invoke(ContainerInterface $container): CssService
    {
        return new CssService($container->get(Css::class));
    }
}
