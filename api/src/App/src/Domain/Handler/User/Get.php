<?php

declare(strict_types=1);

namespace App\Domain\Handler\User;

use App\Domain\Service\UsersServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Core\Crud\CrudInterface;
use Zend\Diactoros\Response\JsonResponse;
use App\Domain\Service\Exception\UserNotFoundException;

final class Get implements MiddlewareInterface, CrudInterface
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
        $id = $request->getAttribute('id_user');

        try {
            $user = $this->usersService->getById((int) $id);
        } catch (UserNotFoundException $e) {
            return new JsonResponse([
                'code' => '404',
                'message' => $e->getMessage()
            ], 404);
        }

        return new JsonResponse($user);
    }
}
