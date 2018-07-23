<?php

declare(strict_types=1);

namespace App\Domain\Handler\Css;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Middleware\TemplateFormatterInterface;
use Zend\Diactoros\Response\TextResponse;

final class Download implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (strpos(end(explode('/', $request->getUri()->getPath())), '.css')) {

            $style = $request->getAttribute(TemplateFormatterInterface::class)['style'];

            $file = md5($style->getId() . $style->getName() . $style->getCreatedAt()) . '.css';

            return new TextResponse($style->getStyle(), 200, [
                'Content-Type' => ['text/css'],
                'Content-Disposition' => "attachment; filename={$file}"
            ]);
        }

        return $handler->handle($request);
    }
}
