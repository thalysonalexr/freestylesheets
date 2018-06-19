<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Twig\TwigRenderer;
use Zend\Diactoros\Response\XmlResponse;
use App\Middleware\TemplateFormatterInterface;

final class XmlFormatter implements MiddlewareInterface, TemplateFormatterInterface
{
    /**
     * @var TwigRenderer
     */
    private $template;

    public function __construct(TwigRenderer $template)
    {
        $this->template = $template;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        if ($request->getHeaderLine('Accept') === 'application/xml') {

            $data = $request->getAttribute(TemplateFormatterInterface::class);

            $template = 'xml::' . key($data) . '.xml.twig';

            return new XmlResponse($this->template->render($template, $data));
        }

        return $handler->handle($request);
    }
}
