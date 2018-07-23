<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Repository;

use Doctrine\DBAL\Connection;
use Psr\Container\ContainerInterface;

final class SqlRepositoryFactory
{
    /**
     * @param ContainerInterface $container     container dependencies
     * @param string $name                      name of class
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, string $name)
    {
        $className = $this->addSqlToClassName($name);

        return new $className(
            $container->get(Connection::class)
        );
    }

    /**
     * Add extension Sql for class
     *
     * @param string $name      name to class
     * @return string
     */
    private function addSqlToClassName(string $name): string
    {
        $exploded = explode('\\', $name);
        $interfaceName = array_pop($exploded);
        array_push($exploded, 'Sql' . $interfaceName);
        return implode('\\', $exploded);
    }
}
