<?php

declare(strict_types=1);

namespace App\Core\Factory;

use Psr\Container\ContainerInterface;
use App\Domain\Service\CssServiceInterface;
use App\Domain\Service\UsersServiceInterface;
use App\Domain\Service\LogsServiceInterface;
use App\Domain\Handler\Css\Create;

final class CssLogHandlerFactory
{
    public function __invoke(ContainerInterface $container): Create
    {
        return new Create(
            $container->get(CssServiceInterface::class),
            $container->get(UsersServiceInterface::class),
            $container->get(LogsServiceInterface::class)
        );
    }
}
