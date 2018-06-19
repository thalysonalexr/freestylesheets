<?php

declare(strict_types=1);

namespace App\Core\Domain\Service;

use App\Domain\Service\LogsService;
use App\Infrastructure\Repository\Logs;
use Psr\Container\ContainerInterface;

final class LogsServiceFactory
{
    public function __invoke(ContainerInterface $container): LogsService
    {
        return new LogsService($container->get(Logs::class));
    }
}
