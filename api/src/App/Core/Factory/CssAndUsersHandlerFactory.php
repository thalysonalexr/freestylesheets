<?php

declare(strict_types=1);

namespace App\Core\Factory;

use Psr\Container\ContainerInterface;
use App\Domain\Service\CssServiceInterface;
use App\Domain\Service\UsersServiceInterface;

final class CssAndUsersHandlerFactory
{
    public function __invoke(ContainerInterface $container, string $name)
    {
        return new $name(
            $container->get(CssServiceInterface::class),
            $container->get(UsersServiceInterface::class)
        );
    }
}
