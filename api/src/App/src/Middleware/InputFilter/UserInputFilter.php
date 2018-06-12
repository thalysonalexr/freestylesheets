<?php

declare(strict_types=1);

namespace App\Middleware\InputFilter;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\InputFilter\InputFilter;
use Zend\Diactoros\Response\JsonResponse;

final class UserInputFilter implements MiddlewareInterface, InputFilterInterface
{
    /**
     * @var InputFilter
     */
    private $filters;

    public function __construct(InputFilter $filters)
    {
        $this->filters = $filters;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getParsedBody();

        $this->filters->setData($data);

        if ( ! $this->filters->isValid()) {
            return new JsonResponse([
                'code' => '400',
                'message' => $this->filters->getMessages()
            ]);
        }

        return $handler->handle($request);
    }
}
