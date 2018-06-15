<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Domain\Service\LogsServiceInterface;
use Zend\Diactoros\Response\EmptyResponse;
use Firebase\JWT\JWT;

final class CheckBlacklist implements MiddlewareInterface
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
        $token = sscanf($request->getHeader('Authorization')[0], "Bearer %s")[0];

        $payload = JWT::decode($token, $this->jwtSecret, ['HS256']);

        if ($this->log->tokenInBlacklist($payload->jti)) {
            return new EmptyResponse(401);
        }

        return $handler->handle($request);
    }
}
