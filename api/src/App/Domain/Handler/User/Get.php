<?php

declare(strict_types=1);

namespace App\Domain\Handler\User;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Core\Crud\UsersCrudInterface;
use App\Domain\Service\UsersServiceInterface;
use App\Domain\Service\Exception\UserNotFoundException;
use App\Middleware\TemplateFormatterInterface;
use Zend\Diactoros\Response\JsonResponse;

final class Get implements MiddlewareInterface, UsersCrudInterface
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

        $data['user'] = $user;

        return $handler->handle($request->withAttribute(TemplateFormatterInterface::class, $data));
    }
}
