<?php

declare(strict_types=1);

return [
    // Provides application-wide services.
    // We recommend using fully-qualified class names whenever possible as
    // service names.
    'dependencies' => [
        // Use 'aliases' to alias a service name to another service. The
        // key is the alias name, the value is the service to which it points.
        'aliases' => [
            // Fully\Qualified\ClassOrInterfaceName::class => Fully\Qualified\ClassName::class,
        ],
        // Use 'invokables' for constructor-less services, or services that do
        // not require arguments to the constructor. Map a service name to the
        // class name.
        'invokables' => [
            // Fully\Qualified\InterfaceName::class => Fully\Qualified\ClassName::class,
        ],
        // Use 'factories' for services provided by callbacks/factory classes.
        'factories'  => [
            // Fully\Qualified\ClassName::class => Fully\Qualified\FactoryName::class,
            \Doctrine\DBAL\Connection::class => \App\Core\Factory\RelationalManagerFactory::class,

            // actions | users
            \App\Domain\Handler\User\Auth::class => \App\Core\Factory\AuthHandlerFactory::class,
            \App\Domain\Handler\User\ForgotPassword::class => \App\Core\Factory\ForgotPasswordFactory::class,
            \App\Domain\Handler\User\ChangePassword::class => \App\Core\Factory\AuthHandlerFactory::class,
            \App\Domain\Handler\User\Create::class => \App\Core\Factory\UsersHandlerFactory::class,
            \App\Domain\Handler\User\GetAll::class => \App\Core\Factory\UsersHandlerFactory::class,
            \App\Domain\Handler\User\Get::class => \App\Core\Factory\UsersHandlerFactory::class,
            \App\Domain\Handler\User\Patch::class => \App\Core\Factory\UsersHandlerFactory::class,
            \App\Domain\Handler\User\Put::class => \App\Core\Factory\UsersHandlerFactory::class,
            \App\Domain\Handler\User\Delete::class => \App\Core\Factory\UsersHandlerFactory::class,

            // services
            \App\Domain\Service\UsersServiceInterface::class => \App\Core\Domain\Service\UsersServiceFactory::class,
            
            // repositories
            \App\Infrastructure\Repository\Users::class => \App\Core\Infrastructure\Repository\SqlRepositoryFactory::class,
            
            // middlewares
            \App\Middleware\Authentication::class => \App\Core\Middleware\JwtAuthenticationFactory::class,
            \App\Middleware\Authorization::class => \App\Core\Middleware\AuthorizationFactory::class,
            \App\Middleware\HtmlResponse::class => \App\Core\Middleware\TemplateResponseFactory::class,
            \App\Middleware\XmlResponse::class => \App\Core\Middleware\TemplateResponseFactory::class,
            \App\Middleware\SendMail::class => \App\Core\Middleware\SendMailFactory::class
        ],
    ],
];
