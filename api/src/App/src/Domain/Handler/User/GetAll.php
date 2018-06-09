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
use App\Middleware\TemplateResponseInterface;

final class GetAll implements MiddlewareInterface, CrudInterface
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
        $data = $this->usersService->getAll();

        if ($request->getHeader('Accept')[0] === 'application/json') {
            return new JsonResponse($data);
        }

        $users['users'] = $data;

        return $handler->handle($request->withAttribute(TemplateResponseInterface::class, [
            self::class,
            $users
        ]));
    }
}
