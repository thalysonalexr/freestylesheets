<?php

declare(strict_types=1);

namespace App\Core\Middleware;

use App\Domain\Service\LogsServiceInterface;
use Psr\Container\ContainerInterface;
use App\Middleware\CheckBlacklist;

final class JtiAuthenticationFactory
{
    public function __invoke(ContainerInterface $container): CheckBlacklist
    {
        return new CheckBlacklist(
            $container->get(LogsServiceInterface::class),
            $container->get('config')['jwt']['secret']
        );
    }
}
