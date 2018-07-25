<?php

declare(strict_types=1);

namespace App\Domain\Handler\User;

use App\Domain\Service\UsersServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Core\Crud\UsersHalInterface;
use App\Infrastructure\Hydrators\UsersCollection;
use App\Middleware\TemplateFormatterInterface;
use Zend\Expressive\Hal\ResourceGenerator;
use Zend\Expressive\Hal\HalResponseFactory;

final class GetAll implements MiddlewareInterface, UsersHalInterface
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
        $filters = $request->getQueryParams();
        $page = $request->getQueryParams()['page'] ?? 1;

        $users = $this->usersService->getAll($filters);

        if ($request->getHeaderLine('Accept') === 'application/json' || $request->getHeaderLine('Accept') === 'application/xml') {
            $resource = $this->resourceGenerator->fromObject(
                (new UsersCollection($users))->setItemCountPerPage(25)->setCurrentPageNumber($page)
            , $request);

            return $this->responseFactory->createResponse($request, $resource);
        }

        $data['users'] = $users;

        return $handler->handle($request->withAttribute(TemplateFormatterInterface::class, $data));
    }
}
