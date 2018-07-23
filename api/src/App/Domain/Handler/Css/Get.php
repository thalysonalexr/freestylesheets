<?php

declare(strict_types=1);

namespace App\Domain\Handler\Css;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Domain\Service\CssServiceInterface;
use App\Domain\Service\Exception\StyleNotFoundException;
use App\Domain\Service\Exception\StyleNotApprovedException;
use App\Core\Crud\CssHalInterface;
use App\Middleware\TemplateFormatterInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Hal\ResourceGenerator;
use Zend\Expressive\Hal\HalResponseFactory;

final class Get implements MiddlewareInterface, CssHalInterface
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
        $id = $request->getAttribute('id');

        try {
            $css = $this->cssService->getByIdApproved((int) $id);
        } catch (StyleNotFoundException $e) {
            return new JsonResponse([
                'code' => '404',
                'message' => $e->getMessage()
            ], 404);

        } catch(StyleNotApprovedException $e) {
            return new JsonResponse([
                'code' => '401',
                'message' => $e->getMessage()
            ], 401);
        }

        if ($request->getHeaderLine('Accept') === 'application/json' || $request->getHeaderLine('Accept') === 'application/xml') {
            $resource = $this->resourceGenerator->fromObject($css, $request);

            return $this->responseFactory->createResponse($request, $resource);
        }

        $data['style'] = $css;

        return $handler->handle($request->withAttribute(TemplateFormatterInterface::class, $data));
    }
}
