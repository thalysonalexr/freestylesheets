<?php

declare(strict_types=1);

namespace App\Domain\Handler\Css;

use App\Core\Crud\CssCrudInterface;
use App\Domain\Service\CssServiceInterface;
use App\Domain\Service\UsersServiceInterface;
use App\Domain\Service\LogsServiceInterface;
use App\Domain\Service\Exception\UserNotFoundException;
use App\Domain\Service\Exception\StyleExistsException;
use App\Domain\Value\Tag;
use App\Domain\Value\Category;
use App\Domain\Value\Author;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Tuupola\Middleware\JwtAuthentication;
use Zend\Diactoros\Response\JsonResponse;
use App\Domain\Service\LogsService;

final class Create implements MiddlewareInterface, CssCrudInterface
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
     * @var LogsServiceInterface
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

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $body = $request->getParsedBody();

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

        $author = Author::fromNativeData(
            $user->getId(), $user->getName(), $user->getEmail()
        );

        $category = Category::fromNativeData(
            null, $body['category_name'], $body['category_description']
        );

        $tag = Tag::fromNativeData(
            null, $body['tag_element'], $body['tag_description'], $category
        );

        try {
            $id = $this->cssService->register(
                $body['name'],
                $body['description'],
                $body['style'],
                $author,
                $tag
            );
        } catch (StyleExistsException $e) {
            return new JsonResponse([
                'code' => '400',
                'message' => $e->getMessage()
            ], 400);
        }

        return new JsonResponse([
            'id' => $id
        ], 201);
    }
}
