<?php

declare(strict_types=1);

namespace App\Domain\Handler\User;

use App\Core\Crud\CrudInterface;
use App\Domain\Service\UsersServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Firebase\JWT\JWT;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\Response\RedirectResponse;

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

    public function __construct(UsersServiceInterface $usersService, string $jwtSecret)
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
            $error['code'] = '500';
            $error['message'] = $e->getMessage();
        }

        if (isset($error['code'])) {
            return new JsonResponse([
                'code' => $error['code'],
                'message' => $error['message']
            ]);
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
