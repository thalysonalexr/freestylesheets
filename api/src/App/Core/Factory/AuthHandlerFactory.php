<?php

declare(strict_types=1);

namespace App\Core\Factory;

use App\Domain\Handler\User\Auth;
use App\Domain\Service\UsersServiceInterface;
use Interop\Container\ContainerInterface;

final class AuthHandlerFactory
{
    public function __invoke(ContainerInterface $container, string $name): Auth
    {
        return new Auth(
            $container->get(UsersServiceInterface::class),
            $container->get('config')['jwt']['secret']
        );
    }
}
