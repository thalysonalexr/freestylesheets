<?php

declare(strict_types=1);

namespace App\Domain\Handler\User;

use App\Core\Crud\CrudInterface;
use App\Domain\Service\UsersServiceInterface;
use App\Domain\Service\Exception\UserNotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use PHPMailer\PHPMailer\PHPMailer;
use Zend\Expressive\Twig\TwigRenderer;
use Tuupola\Base62;
use Firebase\JWT\JWT;
use Zend\Diactoros\Response\JsonResponse;
use App\Middleware\SendMail;

final class ForgotPassword implements MiddlewareInterface
{
    /**
     * @var UsersServiceInterface
     */
    private $usersService;
    /**
     * @var TwigRenderer
     */
    private $template;
    /**
     * @var string
     */
    private $jwtSecret;

    public function __construct(UsersServiceInterface $usersService, TwigRenderer $template, string $jwtSecret)
    {
        $this->usersService = $usersService;
        $this->template = $template;
        $this->jwtSecret = $jwtSecret;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $data = $request->getParsedBody();

        if ( ! isset($data['email']) || ! filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return new JsonResponse([
                'code' => '400',
                'message' => 'The request requires a valid email'
            ], 400);
        }

        try {
            $user = $this->usersService->getByEmail($data['email']);
        } catch (UserNotFoundException $e) {
            return new JsonResponse([
                'code' => '404',
                'message' => $e->getMessage()
            ], 404);
        }

        $future = new \DateTime('+12 hours');

        $payload = [
            'iat' => (new \DateTime())->getTimestamp(),
            'exp' => $future->getTimestamp(),
            'jti' => (new Base62)->encode(random_bytes(16)),
            'data' => [
                'id' => (string) $user->getId(),
                'email' => $user->getEmail()
            ]
        ];

        $data['name'] = $user->getName();
        $data['token'] = JWT::encode($payload, $this->jwtSecret, 'HS256');

        $mail['to'] = 'tha.motog@gmail.com';
        $mail['name'] = $user->getName();
        $mail['from'] = $data['email'];
        $mail['subject'] = 'Password recovery';
        $mail['message'] = $this->template->render('app::forgot-password', $data);

        return $handler->handle($request->withAttribute(SendMail::class, $mail));
    }
}
