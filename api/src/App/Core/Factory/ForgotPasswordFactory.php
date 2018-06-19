<?php

declare(strict_types=1);

namespace App\Core\Factory;

use App\Domain\Handler\User\ForgotPassword;
use App\Domain\Service\UsersServiceInterface;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

final class ForgotPasswordFactory
{
    public function __invoke(ContainerInterface $container): ForgotPassword
    {
        return new ForgotPassword(
            $container->get(UsersServiceInterface::class),
            $container->get(TemplateRendererInterface::class),
            $container->get('config')['jwt']['secret']
        );
    }
}
