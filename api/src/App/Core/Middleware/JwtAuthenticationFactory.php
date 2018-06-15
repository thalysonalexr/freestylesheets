<?php

declare(strict_types=1);

namespace App\Core\Middleware;

use Interop\Container\ContainerInterface;
use Tuupola\Middleware\JwtAuthentication;

final class JwtAuthenticationFactory
{
    public function __invoke(ContainerInterface $container): JwtAuthentication
    {
        return new JwtAuthentication([
            'secret' => $container->get('config')['jwt']['secret'],
            'secure' => false,
            'algorithm' => ['HS256'],
            'attribute' => JwtAuthentication::class
        ]);
    }
}
