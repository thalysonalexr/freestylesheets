<?php

declare(strict_types=1);

namespace App\Handler\Middleware;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use PHPMailer\PHPMailer\PHPMailer;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;

final class ContactPostHandler implements MiddlewareInterface
{
    /**
     * @var key for flash message
     */
    const FLASH_KEY = 'form-contact';
    /**
     * @var PHPMailer
     */
    private $mail;
    /**
     * @var TemplateRendererInterface
     */
    private $template;

    public function __construct(PHPMailer $mail, TemplateRendererInterface $template)
    {
        $this->mail = $mail;
        $this->template = $template;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $errors = $request->getAttribute(\App\Handler\Middleware\InputFilter\ContactInputFilter::class);

        $flashMessages = $request->getAttribute(\Zend\Expressive\Flash\FlashMessageMiddleware::FLASH_ATTRIBUTE);

        $flashMessages->flashNow(self::FLASH_KEY, $errors);

        if ( ! $errors['errors']) {
            $data = $request->getParsedBody();

            $this->setDataMail($data);

            try {
                $this->mail->send();
                return new HtmlResponse($this->template->render('pages::contact-thanks', $data));
            } catch (\PHPMailer\PHPMailer\Exception $e) {
                $flashMessages->flash(self::FLASH_KEY, $e->getMessage());
            }
        }

        return new HtmlResponse($this->template->render('pages::contact', $flashMessages->getFlash(self::FLASH_KEY)));
    }

    public function setDataMail($data)
    {
        $this->mail->setFrom($data['email']);
        $this->mail->addAddress('tha.motog@gmail.com');
        $this->mail->FromName = self::getFullName($data);
        $this->mail->Subject = $data['subject'];
        $this->mail->Body = $data['message'];
        $this->mail->MsgHTML($data["message"]);
    }

    public static function getFullName($data)
    {
        return $data['firstName'] . ' ' . $data['lastName'];
    }
}
