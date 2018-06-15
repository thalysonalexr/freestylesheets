<?php

declare(strict_types=1);

namespace App\Domain\Handler\User;

use App\Domain\Service\UsersServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Core\Crud\UsersCrudInterface;
use Zend\Diactoros\Response\JsonResponse;
use App\Domain\Service\Exception\UserEmailExistsException;

final class Put implements MiddlewareInterface, UsersCrudInterface
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

        $body = $request->getParsedBody();

        try {
            $count = $this->usersService->edit((int) $id, $body);
        } catch (UserEmailExistsException $e) {
            return new JsonResponse([
                'code' => '400',
                'message' => $e->getMessage()
            ], 400);
        }

        if (1 === $count) {
            return new JsonResponse([
                'code' => '200',
                'message' => 'Update success'
            ], 200);
        }

        return new JsonResponse([
            'code' => '304',
            'message' => 'Unmodified'
        ], 304);
    }
}
