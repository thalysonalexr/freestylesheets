<?php

declare(strict_types=1);

namespace App\Core\Factory;

use Psr\Container\ContainerInterface;
use App\Domain\Handler\Css\LinksEmbedded;
use App\Domain\Service\CssServiceInterface;
use Zend\Expressive\Router\RouterInterface;

final class LinksEmbeddedFactory
{
    public function __invoke(ContainerInterface $container) : LinksEmbedded
    {
        return new LinksEmbedded(
            $container->get(CssServiceInterface::class),
            $container->get(RouterInterface::class)
        );
    }
}
