<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Tuupola\Middleware\JwtAuthentication;
use Zend\Expressive\Twig\TwigRenderer;
use Zend\Diactoros\Response\HtmlResponse as Html;
use App\Domain\Handler\User\GetAll;
use App\Domain\Handler\User\Get;

final class HtmlResponse implements MiddlewareInterface
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
        if ($users = $request->getAttribute(GetAll::class)) {
            $data['users'] = $users;
        } else {
            $data['user'] = $request->getAttribute(Get::class);
        }

        return new Html($this->template->render('app::list-users', $data));
    }
}
