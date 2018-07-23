<?php

declare(strict_types=1);

namespace App\Domain\Handler\Css;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Domain\Service\CssServiceInterface;
use App\Domain\Service\UsersServiceInterface;
use App\Domain\Service\Exception\UserNotFoundException;
use App\Domain\Service\Exception\StyleExistsException;
use App\Domain\Value\Tag;
use App\Domain\Value\Author;
use Tuupola\Middleware\JwtAuthentication;
use Zend\Diactoros\Response\JsonResponse;

final class Create implements MiddlewareInterface
{
    /**
     * @var CssServiceInterface
     */
    private $cssService;
    /**
     * @var UsersServiceInterface
     */
    private $usersService;

    public function __construct(
        CssServiceInterface $cssService,
        UsersServiceInterface $usersService
    )
    {
        $this->cssService = $cssService;
        $this->usersService = $usersService;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $body = $request->getParsedBody();

        $email = $request->getAttribute(JwtAuthentication::class)['data']->email;

        try {
            $user = $this->usersService->getByEmail($email);
        } catch (UserNotFoundException $e) {
            return new JsonResponse([
                'code' => '404',
                'message' => 'This user is not valid'
            ], 404);
        }

        $author = Author::fromNativeData(
            $user->getId(), $user->getName(), $user->getEmail()
        );

        if (
            isset($body['tag_element'])     &&
            isset($body['tag_description']) &&
            isset($body['category_name'])   &&
            isset($body['category_description'])
        ) {
            $tag = Tag::fromNativeData(
                null,
                $body['tag_element'],
                $body['tag_description'],
                null,
                $body['category_name'],
                $body['category_description']
            );
        } else {
            $tag = null;
        }

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
