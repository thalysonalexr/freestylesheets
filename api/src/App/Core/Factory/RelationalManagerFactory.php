<?php

declare(strict_types=1);

namespace App\Core\Factory;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Psr\Container\ContainerInterface;

final class RelationalManagerFactory
{
    public function __invoke(ContainerInterface $container): Connection
    {
        $config = $container->get('config')['doctrine']['connection']['default'];

        return DriverManager::getConnection($config);
    }
}
