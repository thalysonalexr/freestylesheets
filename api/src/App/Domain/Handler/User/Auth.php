<?php

declare(strict_types=1);

namespace App\Domain\Handler\User;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Domain\Service\UsersServiceInterface;
use App\Domain\Service\LogsServiceInterface;
use App\Domain\Service\Exception\UserNotFoundException;
use App\Domain\Value\Exception\WrongPasswordException;
use App\Domain\Value\Password;
use Tuupola\Base62;
use Firebase\JWT\JWT;
use Zend\Diactoros\Response\JsonResponse;

final class Auth implements MiddlewareInterface
{
    /**
     * @var UsersServiceInterface
     */
    private $usersService;
    /**
     * @var LogsServiceInterface
     */
    private $log;
    /**
     * @var string
     */
    private $jwtSecret;

    public function __construct(UsersServiceInterface $usersService, LogsServiceInterface $log, string $jwtSecret)
    {
        $this->usersService = $usersService;
        $this->log = $log;
        $this->jwtSecret = $jwtSecret;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $data = $request->getParsedBody();

        try {
            $user = $this->usersService->getByEmail($data['email']);
        } catch (UserNotFoundException $e) {
            return new JsonResponse([
                'code' => '401',
                'message' => $e->getMessage()
            ], 401);
        }

        try {
           Password::verify($data['password'], $user->getPassword());
        } catch (WrongPasswordException $e) {
            $this->log->login((int) $user->getId(), false);
            return new JsonResponse([
                'code' => '401',
                'message' => $e->getMessage()
            ], 401);
        }

        $this->log->login((int) $user->getId(), true);

        $future = new \DateTime('+60 minutes');

        $payload = [
            'iat' => (new \DateTime())->getTimestamp(),
            'exp' => $future->getTimestamp(),
            'jti' => (new Base62)->encode(random_bytes(16)),
            'data' => [
                'id' => (string) $user->getId(),
                'email' => $user->getEmail()
            ]
        ];

        $token = JWT::encode($payload, $this->jwtSecret, 'HS256');

        return new JsonResponse([
            'token' => $token,
            'expires' => $future->getTimestamp()
        ]);
    }
}
