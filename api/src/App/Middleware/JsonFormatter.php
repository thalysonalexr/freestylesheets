<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use App\Middleware\TemplateFormatterInterface;

final class JsonFormatter implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->getHeaderLine('Accept') === 'application/json') {
            return new JsonResponse($request->getAttribute(TemplateFormatterInterface::class));
        }

        return $handler->handle($request);
    }
}
