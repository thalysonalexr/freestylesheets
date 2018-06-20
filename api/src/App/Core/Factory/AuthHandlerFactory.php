<?php

declare(strict_types=1);

namespace App\Core\Factory;

use App\Domain\Service\UsersServiceInterface;
use App\Domain\Service\LogsServiceInterface;
use Psr\Container\ContainerInterface;
use App\Domain\Handler\User\Auth;

final class AuthHandlerFactory
{
    public function __invoke(ContainerInterface $container) : Auth
    {
        return new Auth(
            $container->get(UsersServiceInterface::class),
            $container->get(LogsServiceInterface::class),
            $container->get('config')['jwt']['secret']
        );
    }
}
