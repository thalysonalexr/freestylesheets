<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class TemplateRendererFactory
{
    public function __invoke(ContainerInterface $container, string $name) : RequestHandlerInterface
    {
        return new $name($container->get(TemplateRendererInterface::class));
    }
}
