<?php

declare(strict_types=1);

namespace App\Domain\Handler\Css;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Domain\Service\CssServiceInterface;
use App\Domain\Service\UsersServiceInterface;
use App\Domain\Service\LogsServiceInterface;
use App\Core\Crud\CssCrudInterface;
use App\Middleware\TemplateFormatterInterface;

final class GetAll implements MiddlewareInterface, CssCrudInterface
{
    /**
     * @var CssServiceInterface
     */
    private $cssService;

    public function __construct(CssServiceInterface $cssService)
    {
        $this->cssService = $cssService;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $filters = $request->getQueryParams();

        $css['styles'] = $this->cssService->getAll($filters);

        return $handler->handle($request->withAttribute(TemplateFormatterInterface::class, $css));
    }
}
