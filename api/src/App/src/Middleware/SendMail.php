<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use PHPMailer\PHPMailer\PHPMailer;

final class SendMail implements MiddlewareInterface
{
    /**
     * @var PHPMailer
     */
    private $mail;

    public function __construct(PHPMailer $mail)
    {
        $this->mail = $mail;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $data = $request->getAttribute(self::class);

        try {
            $this->send($data);
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            return new JsonResponse([
                'code' => '500',
                'message' => $e->getMessage()
            ], 500);
        }

        return new JsonResponse([
            'code' => '200',
            'message' => 'Message has been sent'
        ]);
    }

    public function send(array $data = [])
    {
        $this->mail->setFrom($data["from"]);
        $this->mail->addAddress($data["to"]);
        $this->mail->FromName = $data["name"];
        $this->mail->Subject = $data["subject"];
        $this->mail->Body = $data["message"];
        $this->mail->MsgHTML($data["message"]);

        return $this->mail->send();
    }
}
