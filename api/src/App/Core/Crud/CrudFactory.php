<?php

namespace App\Core\Crud;

use Psr\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;

final class CrudFactory
{
    public function __invoke(ContainerInterface $container, $requestedName)
    {
        $relational = $container->get('App\Core\RelationalManager');
        return new $requestedName($relational);
    }
}
