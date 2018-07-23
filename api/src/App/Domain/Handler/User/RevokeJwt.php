<?php

declare(strict_types=1);

namespace App\Domain\Handler\User;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Domain\Service\LogsServiceInterface;
use App\Domain\Service\Exception\JtiAlreadyExistsException;
use App\Domain\Value\Jti;
use Zend\Diactoros\Response\EmptyResponse;
use Zend\Diactoros\Response\JsonResponse;
use Firebase\JWT\JWT;

final class RevokeJwt implements MiddlewareInterface
{
    /**
     * @var LogsServiceInterface
     */
    private $logsService;
    /**
     * @var string
     */
    private $jwtSecret;

    public function __construct(LogsServiceInterface $logsService, string $jwtSecret)
    {
        $this->logsService = $logsService;
        $this->jwtSecret = $jwtSecret;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $query = $request->getQueryParams();

        $payload = JWT::decode($query['token'], $this->jwtSecret, ['HS256']);

        try {
            if ($this->logsService->revokeJwt(Jti::new($payload->jti))) {
                return new EmptyResponse();
            }
        } catch (JtiAlreadyExistsException $e) {
            return new JsonResponse([
                'code' => '500',
                'message' => $e->getMessage()
            ]);
        }

        return new JsonResponse([
            'code' => '500',
            'message' => 'Internal server error'
        ]);
    }
}
