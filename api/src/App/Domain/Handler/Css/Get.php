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
use App\Core\Crud\CssCrudInterface;
use App\Middleware\TemplateFormatterInterface;
use Zend\Diactoros\Response\JsonResponse;

final class Get implements MiddlewareInterface, CssCrudInterface
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
        $id = $request->getAttribute('id_style');

        try {
            $style = $this->cssService->getByIdApproved((int) $id);
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

        $data['style'] = $style;

        return $handler->handle($request->withAttribute(TemplateFormatterInterface::class, $data));
    }
}
