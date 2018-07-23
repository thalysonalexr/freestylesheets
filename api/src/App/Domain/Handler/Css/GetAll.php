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
use App\Core\Crud\CssHalInterface;
use App\Infrastructure\Hydrators\CssCollection;
use App\Middleware\TemplateFormatterInterface;
use Zend\Expressive\Hal\ResourceGenerator;
use Zend\Expressive\Hal\HalResponseFactory;

final class GetAll implements MiddlewareInterface, CssHalInterface
{
    /**
     * @var CssServiceInterface
     */
    private $cssService;
    /**
     * @var ResourceGenerator
     */
    private $resourceGenerator;
    /**
     * @var HalResponseFactory
     */
    private $responseFactory;

    public function __construct(
        CssServiceInterface $cssService,
        ResourceGenerator $resourceGenerator,
        HalResponseFactory $responseFactory
    )
    {
        $this->cssService = $cssService;
        $this->resourceGenerator = $resourceGenerator;
        $this->responseFactory = $responseFactory;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $filters = $request->getQueryParams();

        $css = $this->cssService->getAll($filters);

        if ($request->getHeaderLine('Accept') === 'application/json' || $request->getHeaderLine('Accept') === 'application/xml') {
            $resource = $this->resourceGenerator->fromObject(new CssCollection($css), $request);

            return $this->responseFactory->createResponse($request, $resource);
        }

        $data['styles'] = $css;

        return $handler->handle($request->withAttribute(TemplateFormatterInterface::class, $data));
    }
}
