<?php

declare(strict_types=1);

namespace App\Domain\Handler\User;

use App\Core\Crud\UsersCrudInterface;
use App\Domain\Service\UsersServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use App\Domain\Service\Exception\UserEmailExistsException;

final class Create implements MiddlewareInterface, UsersCrudInterface
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
        $body = $request->getParsedBody();

        try {
            $id = $this->usersService->register(
                $body['name'],
                $body['email'],
                $body['password'],
                (bool) $body['admin']
            );
        } catch (UserEmailExistsException $e) {
            return new JsonResponse([
                'code' => '400',
                'message' => $e->getMessage()
            ], 400);
        }

        return new JsonResponse([
            'id' => $id
        ], 201);
    }
}
