<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Middleware\TemplateFormatterInterface;
use Zend\Diactoros\Response\TextResponse;

final class CssFormatter implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->getHeaderLine('Accept') === 'text/css') {

            $style = $request->getAttribute(TemplateFormatterInterface::class)['style'];

            return new TextResponse($style->getStyle(), 200, ['Content-Type' => ['text/css']]);
        }

        return $handler->handle($request);
    }
}
