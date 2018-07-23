<?php

declare(strict_types=1);

namespace App\Domain\Handler\User;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Domain\Service\UsersServiceInterface;
use App\Domain\Service\Exception\UserNotFoundException;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\Response\RedirectResponse;
use Firebase\JWT\JWT;

final class ChangePassword implements MiddlewareInterface
{
    /**
     * @var UsersServiceInterface
     */
    private $usersService;
    /**
     * @var string
     */
    private $jwtSecret;

    public function __construct(
        UsersServiceInterface $usersService,
        string $jwtSecret
    )
    {
        $this->usersService = $usersService;
        $this->jwtSecret = $jwtSecret;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $token = $request->getQueryParams();

        if ( ! isset($token['token']) || empty($token['token'])) {
            return new RedirectResponse('/');
        }

        $data = $request->getParsedBody();

        try {
            $token = JWT::decode($token['token'], $this->jwtSecret, ['HS256']);
        } catch (\Exception $e) {
            return new JsonResponse([
                'code' => '500',
                'message' => $e->getMessage()
            ]);
        }

        try {
            $user = $this->usersService->getById((int) $token->data->id);
        } catch (UserNotFoundException $e) {
            return new JsonResponse([
                'code' => '404',
                'message' => $e->getMessage()
            ], 404);
        }

        $updated = $this->usersService->updatePassword((int) $token->data->id, $data);

        if (1 === $updated) {
            return new JsonResponse([
                'code' => '200',
                'message' => 'Update success'
            ], 200);
        }

        return new JsonResponse([
            'code' => '500',
            'message' => 'Internal server error'
        ]);
    }
}
