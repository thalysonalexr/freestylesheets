<?php

declare(strict_types=1);

namespace App\Domain\Handler\User;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Core\Crud\UsersCrudInterface;
use App\Domain\Service\UsersServiceInterface;
use Zend\Diactoros\Response\JsonResponse;

final class Patch implements MiddlewareInterface, UsersCrudInterface
{
    /**
     * @var UsersServiceInterface
     */
    private $usersService;

    public function __construct(UsersServiceInterface $usersService)
    {
        $this->usersService = $usersService;
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
                'message' => 'Updated user successfully'
            ], 200);
        }

        return new JsonResponse([
            'code' => '304',
            'message' => 'Unmodified'
        ], 304);
    }
}
