<?php

declare(strict_types=1);

namespace App\Core\Factory;

use App\Domain\Service\LogsServiceInterface;
use Psr\Container\ContainerInterface;

final class LogsHandlerFactory
{
    public function __invoke(ContainerInterface $container, string $name)
    {
        return new $name(
            $container->get(LogsServiceInterface::class),
            $container->get('config')['jwt']['secret']
        );
    }
}
