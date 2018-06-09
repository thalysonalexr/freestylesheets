<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Twig\TwigRenderer;
use Zend\Diactoros\Response\XmlResponse as Xml;
use App\Domain\Handler\User\GetAll;
use App\Domain\Handler\User\Get;
use App\Middleware\TemplateResponseInterface;

final class XmlResponse implements MiddlewareInterface, TemplateResponseInterface
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
        if ($request->getHeader('Accept')[0] === 'application/xml') {

            switch ($data[0]) {
                case \App\Domain\Handler\User\Get::class:
                case \App\Domain\Handler\User\GetAll::class:
                    $template = 'app::list-users.xml.twig';
                    break;
                case \App\Domain\Handler\Css\Get::class:
                case \App\Domain\Handler\Css\GetAll::class:
                    $template = 'app::list-css.xml.twig';
                    break;
                case \App\Domain\Handler\Fonts\Get::class:
                case \App\Domain\Handler\Fonts\GetAll::class:
                    $template = 'app::list-fonts.xml.twig';
                    break;
            }

            return new Xml($this->template->render($template, $data));
        }

        return $handler->handle($request);
    }
}
