<?php

declare(strict_types=1);

namespace App\Domain\Handler\User;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Core\Crud\UsersCrudInterface;
use App\Domain\Service\UsersServiceInterface;
use App\Domain\Service\Exception\UserNotFoundException;
use App\Domain\Service\Exception\InvalidStatusException;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\Response\EmptyResponse;

final class Disable implements MiddlewareInterface, UsersCrudInterface
{
    /**
     * @var UsersServiceInterface
     */
    private $usersService;

    public function __construct(UsersServiceInterface $usersService)
    {
        $this->usersService = $usersService;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $id = $request->getAttribute('id');

        try {
            $user = $this->usersService->getById((int) $id);
        } catch (UserNotFoundException $e) {
            return new JsonResponse([
                'code' => '404',
                'message' => $e->getMessage()
            ], 404);
        }

        try {
            $this->usersService->disable($user);
        } catch (InvalidStatusException $e) {
            return new JsonResponse([
                'code' => '304',
                'message' => $e->getMessage()
            ], 304);
        }

        return new EmptyResponse();
    }
}
