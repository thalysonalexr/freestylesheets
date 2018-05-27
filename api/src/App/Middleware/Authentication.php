<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Tuupola\Middleware\JwtAuthentication;

final class Authentication implements MiddlewareInterface
{
    /**
     * @var JwtAuthentication
     */
    private $auth;

    public function __construct(JwtAuthentication $auth)
    {
        $this->auth = $auth;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        return $handler->handle($request);
    }
}
