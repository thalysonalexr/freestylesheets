<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Domain\Service\UsersServiceInterface;
use Tuupola\Middleware\JwtAuthentication;
use Zend\Diactoros\Response\JsonResponse;

final class Authorization implements MiddlewareInterface
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
        $email = $request->getAttribute(JwtAuthentication::class)['data']->email;

        $user = $this->usersService->getByEmail($email);

        if ( ! $user->isAdmin()) {
            return new JsonResponse([
                'code' => '403',
                'message' => 'Forbidden. You do not have privileges for this request.'
            ], 403);
        }

        return $handler->handle($request);
    }
}
