<?php

declare(strict_types=1);

namespace App\Handler\Middleware;

use Psr\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

final class ContactPostHandlerFactory
{
    public function __invoke(ContainerInterface $container) : ContactPostHandler
    {
        $config = $container->get('config')['smtp']['mailtrap.io'];

        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = $config['hostname'];
        $mail->Username = $config['username'];
        $mail->Password = $config['password'];
        $mail->SMTPSecure = $config['auth'];
        $mail->Port = $config['port'];
        $mail->CharSet = $config['charset'];
        $mail->isHTML(true);
        // $mail->SMTPDebug = 2;

        return new ContactPostHandler(
            $mail,
            $container->get(TemplateRendererInterface::class)
        );
    }
}
