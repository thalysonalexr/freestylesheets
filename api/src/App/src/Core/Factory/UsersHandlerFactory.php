<?php

declare(strict_types=1);

namespace App\Core\Factory;

use App\Domain\Service\UsersServiceInterface;
use Interop\Container\ContainerInterface;

final class UsersHandlerFactory
{
    public function __invoke(ContainerInterface $container, string $name)
    {
        return new $name(
            $container->get(UsersServiceInterface::class)
        );
    }
}
