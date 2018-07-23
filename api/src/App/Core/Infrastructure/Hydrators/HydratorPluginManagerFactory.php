<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Hydrators;

use App\Infrastructure\Hydrators\SerializeExtractor;
use Psr\Container\ContainerInterface;
use Zend\Hydrator\HydratorPluginManager;
use Zend\ServiceManager\Factory\InvokableFactory;

final class HydratorPluginManagerFactory
{
    public function __invoke(ContainerInterface $container, string $name, callable $callback): HydratorPluginManager
    {
        $hydratorContainer = $callback();

        $hydratorContainer->setFactory(
            SerializeExtractor::class,
            InvokableFactory::class
        );

        return $hydratorContainer;
    }
}
