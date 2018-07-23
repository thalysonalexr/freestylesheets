<?php

declare(strict_types=1);

namespace App\Core\Middleware;

use App\Middleware\SendMail;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

final class SendMailFactory
{
    public function __invoke(ContainerInterface $container): SendMail
    {
        $config = $container->get('config')['mail']['smtp'];

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

        return new SendMail($mail);
    }
}
