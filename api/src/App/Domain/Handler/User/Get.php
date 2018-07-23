<?php

declare(strict_types=1);

namespace App\Domain\Handler\User;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Core\Crud\UsersHalInterface;
use App\Domain\Service\UsersServiceInterface;
use App\Domain\Service\Exception\UserNotFoundException;
use App\Middleware\TemplateFormatterInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Hal\ResourceGenerator;
use Zend\Expressive\Hal\HalResponseFactory;

final class Get implements MiddlewareInterface, UsersHalInterface
{
    /**
     * @var UsersServiceInterface
     */
    private $usersService;
    /**
     * @var ResourceGenerator
     */
    private $resourceGenerator;
    /**
     * @var HalResponseFactory
     */
    private $responseFactory;

    public function __construct(
        UsersServiceInterface $usersService,
        ResourceGenerator $resourceGenerator,
        HalResponseFactory $responseFactory
    )
    {
        $this->usersService = $usersService;
        $this->resourceGenerator = $resourceGenerator;
        $this->responseFactory = $responseFactory;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $id = $request->getAttribute('id');

        try {
            $user = $this->usersService->getById((int) $id);
        } catch (UserNotFoundException $e) {
            return new JsonResponse([
                'code' => '404',
                'message' => $e->getMessage()
            ], 404);
        }

        if ($request->getHeaderLine('Accept') === 'application/json' || $request->getHeaderLine('Accept') === 'application/xml') {
            $resource = $this->resourceGenerator->fromObject($user, $request);

            return $this->responseFactory->createResponse($request, $resource);
        }

        $data['user'] = $user;

        return $handler->handle($request->withAttribute(TemplateFormatterInterface::class, $data));
    }
}
