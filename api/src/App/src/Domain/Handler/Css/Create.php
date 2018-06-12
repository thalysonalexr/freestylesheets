<?php

declare(strict_types=1);

namespace App\Domain\Handler\Css;

use App\Core\Crud\CssCrudInterface;
use App\Domain\Service\CssServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class Create implements MiddlewareInterface, CssCrudInterface
{
    /**
     * @var CssServiceInterface
     */
    private $cssService;

    public function __construct(CssServiceInterface $cssService)
    {
        $this->cssService = $cssService;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        
    }
}
