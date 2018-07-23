<?php

declare(strict_types=1);

namespace App\Domain\Handler\User;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Middleware\SendMail;
use App\Domain\Value\Jti;
use App\Domain\Value\PasswordRecovery;
use App\Domain\Service\UsersServiceInterface;
use App\Domain\Service\Exception\UserNotFoundException;
use App\Domain\Service\Exception\MaxChangeRecoveryPasswordException;
use Zend\Expressive\Twig\TwigRenderer;
use Zend\Diactoros\Response\JsonResponse;
use Tuupola\Base62;
use Firebase\JWT\JWT;

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

    public function __construct(
        UsersServiceInterface $usersService,
        TwigRenderer $template,
        string $jwtSecret,
        array $recovery
    )
    {
        $this->usersService = $usersService;
        $this->template = $template;
        $this->jwtSecret = $jwtSecret;
        $this->recovery = $recovery;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $data = $request->getParsedBody();

        try {
            $user = $this->usersService->getByEmail($data['email']);
        } catch (UserNotFoundException $e) {
            return new JsonResponse([
                'code' => '404',
                'message' => $e->getMessage()
            ], 404);
        }

        // verify max(10) of request in 5 last days
        try {
            $this->usersService->checkMaxRequireChangePassword($user->getId(), $this->recovery['max_days'], $this->recovery['max_try']);
        } catch (MaxChangeRecoveryPasswordException $e) {
            return new JsonResponse([
                'code' => '500',
                'message' => $e->getMessage()
            ]);
        }

        $jti = Jti::new((new Base62)->encode(random_bytes(16)));
        $future = new \DateTime('+12 hours');

        $payload = [
            'iat' => (new \DateTime())->getTimestamp(),
            'exp' => $future->getTimestamp(),
            'jti' => $jti->getValue(),
            'data' => [
                'id' => (string) $user->getId(),
                'email' => $user->getEmail()
            ]
        ];

        // register request
        $recovery = PasswordRecovery::fromNativeData(null, $jti, (new \DateTime())->format('Y-m-d H:i:s'), $user);

        $this->usersService->recovery($recovery);

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
