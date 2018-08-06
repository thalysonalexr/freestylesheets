<?php

declare(strict_types=1);

namespace App\Domain\Handler\Css;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Core\Crud\CssCrudInterface;
use App\Domain\Service\CssServiceInterface;
use Zend\Diactoros\Response\JsonResponse;

final class Patch implements MiddlewareInterface, CssCrudInterface
{
    /**
     * @var UsersServiceInterface
     */
    private $cssService;

    public function __construct(UsersServiceInterface $cssService)
    {
        $this->cssService = $cssService;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $id = $request->getAttribute('id');

        $body = $request->getParsedBody();

        try {
            $success = $this->usersService->editPartial((int) $id, $body);
        } catch (\Exception $e) {
            return new JsonResponse([
                'code' => '400',
                'message' => $e->getMessage()
            ], 400);
        }

        if ($success) {
            return new JsonResponse([
                'code' => '200',
                'message' => 'Updated style successfully'
            ], 200);
        }

        return new JsonResponse([
            'code' => '304',
            'message' => 'Unmodified'
        ], 304);
    }
}
