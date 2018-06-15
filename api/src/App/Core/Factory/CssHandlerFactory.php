<?php

declare(strict_types=1);

namespace App\Core\Factory;

use Interop\Container\ContainerInterface;
use App\Domain\Service\CssServiceInterface;

final class CssHandlerFactory
{
    public function __invoke(ContainerInterface $container, string $name)
    {
        return new $name(
            $container->get(CssServiceInterface::class)
        );
    }
}
