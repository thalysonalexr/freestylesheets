<?php

declare(strict_types=1);

namespace App\Handler\Middleware\InputFilter;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\InputFilter\InputFilter;
use App\Middleware\InputFilter\InputFilterInterface;

use function is_array;
use function array_push;

final class ContactInputFilter implements MiddlewareInterface, InputFilterInterface
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

        $errors['errors'] = [];

        if ( ! $this->filters->isValid()) {
            foreach ($this->filters->getMessages() as $key => $arr) {
                if (is_array($arr)) {
                    foreach ($arr as $value) {
                        array_push($errors['errors'], ($key . ' - ' . $value));
                    }
                }
            }
        }

        return $handler->handle($request->withAttribute(self::class, $errors));
    }
}
