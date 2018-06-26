<?php

declare(strict_types=1);

namespace App\Domain\Handler\Css;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;
use App\Domain\Service\CssServiceInterface;
use App\Domain\Service\UsersServiceInterface;
use App\Domain\Service\LogsServiceInterface;
use App\Domain\Service\Exception\StyleNotFoundException;
use App\Domain\Service\Exception\StyleNotApprovedException;
use App\Domain\Service\Exception\StyleAlreadyApprovedException;
use Tuupola\Middleware\JwtAuthentication;

final class Approve implements MiddlewareInterface
{
    /**
     * @var CssServiceInterface
     */
    private $cssService;
    /**
     * @var UsersServiceInterface
     */
    private $usersService;
    /** 
     * @var LogsServiceInterface;
     */
    private $logsService;

    public function __construct(
        CssServiceInterface $cssService,
        UsersServiceInterface $usersService,
        LogsServiceInterface $logsService
    )
    {
        $this->cssService = $cssService;
        $this->usersService = $usersService;
        $this->logsService = $logsService;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $id = $request->getAttribute('id_style');

        $email = $request->getAttribute(JwtAuthentication::class)['data']->email;

        try {
            $user = $this->usersService->getByEmail($email);
        } catch (UserNotFoundException $e) {
            $this->logsService->revokeJwt($request->getAttribute(JwtAuthentication::class)['jti']);

            return new JsonResponse([
                'code' => '404',
                'message' => 'This user is not valid'
            ], 404);
        }

        try {
            $style = $this->cssService->getById((int) $id);
        } catch (StyleNotFoundException $e) {
            return new JsonResponse([
                'code' => '404',
                'message' => $e->getMessage()
            ], 404);

        }

        try {
            if ($this->cssService->approve($style, $user)) {
                return new JsonResponse([
                    'code' => '200',
                    'message' => 'Successfully approved style'
                ]);
            }
        } catch (StyleAlreadyApprovedException $e) {
            return new JsonResponse([
                'code' => '304',
                'message' => $e->getMessage()
            ]);
        }
    }
}
