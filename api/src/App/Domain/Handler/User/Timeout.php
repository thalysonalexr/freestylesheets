<?php

declare(strict_types=1);

namespace App\Domain\Handler\User;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Domain\Service\LogsServiceInterface;
use App\Domain\Service\Exception\JtiAlreadyExistsException;
use App\Domain\Value\Jti;
use Zend\Diactoros\Response\EmptyResponse;
use Zend\Diactoros\Response\JsonResponse;
use Firebase\JWT\JWT;

final class Timeout implements MiddlewareInterface
{
    /**
     * @var LogsServiceInterface
     */
    private $log;
    /**
     * @var string
     */
    private $jwtSecret;

    public function __construct(LogsServiceInterface $log, string $jwtSecret)
    {
        $this->log = $log;
        $this->jwtSecret = $jwtSecret;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $query = $request->getQueryParams();

        $payload = JWT::decode($query['token'], $this->jwtSecret, ['HS256']);

        try {
            $process = $this->log->timeout((int) $payload->data->id, Jti::new($payload->jti));
        } catch (JtiAlreadyExistsException $e) {
            return new JsonResponse([
                'code' => '500',
                'message' => $e->getMessage()
            ]);
        }

        if (true === $process) {
            return new EmptyResponse();
        } else {
            return new JsonResponse([
                'code' => '500',
                'message' => 'Internal server error'
            ]);
        }
    }
}
