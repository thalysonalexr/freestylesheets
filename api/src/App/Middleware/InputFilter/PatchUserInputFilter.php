<?php

declare(strict_types=1);

namespace App\Middleware\InputFilter;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\InputFilter\InputFilterPluginManager;
use Zend\Diactoros\Response\JsonResponse;

final class PatchUserInputFilter implements MiddlewareInterface
{
    /**
     * @var InputFilterPluginManager
     */
    private $manager;
    /**
     * @var InputFilter
     */
    private $filters;

    public function __construct(InputFilterPluginManager $manager)
    {
        $this->manager = $manager;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getParsedBody();

        if (count($data) !== 1) {
            return new JsonResponse([
                'code' => '400',
                'message' => 'Permitted to update only one field at a time'
            ], 400);
        }

        $class = $this->selectInputFilter($data);

        if ( ! $class) {
            return new JsonResponse([
                'code' => '400',
                'message' => 'The fields referred to must be "name" or "email"'
            ], 400);
        }
        
        $this->filters = $this->manager->get($class);
        $this->filters->setData($data);

        if ( ! $this->filters->isValid()) {
            return new JsonResponse([
                'code' => '400',
                'message' => $this->filters->getMessages()
            ]);
        }

        return $handler->handle($request);
    }

    public function selectInputFilter(array $data): string
    {
        switch(key($data)) {
            case 'name':
                $filter = \App\Middleware\InputFilter\NameInputFilter::class;
                break;
            case 'email':
                $filter = \App\Middleware\InputFilter\EmailInputFilter::class;
                break;
            default:
                $filter = '';
        }

        return $filter;
    }
}
