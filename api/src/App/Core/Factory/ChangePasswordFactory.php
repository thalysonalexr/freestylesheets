<?php

declare(strict_types=1);

namespace App\Core\Factory;

use App\Domain\Service\UsersServiceInterface;
use App\Domain\Service\LogsServiceInterface;
use Psr\Container\ContainerInterface;
use App\Domain\Handler\User\ChangePassword;

final class ChangePasswordFactory
{
    public function __invoke(ContainerInterface $container) : ChangePassword
    {
        return new ChangePassword(
            $container->get(UsersServiceInterface::class),
            $container->get('config')['jwt']['secret']
        );
    }
}
