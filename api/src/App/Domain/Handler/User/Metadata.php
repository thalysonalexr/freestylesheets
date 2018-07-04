<?php

declare(strict_types=1);

namespace App\Domain\Handler\User;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Domain\Service\UsersServiceInterface;
use App\Core\Crud\UsersCrudInterface;

final class Metadata implements MiddlewareInterface, UsersCrudInterface
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
        return $handler->handle($request)
            ->withHeader('FSS-Total-Users', $this->usersService->getTotal())
            ->withHeader('FSS-Total-Admin', $this->usersService->getTotalAdmin())
            ->withHeader('FSS-Total-Actives', $this->usersService->getTotalActives());
    }
}
