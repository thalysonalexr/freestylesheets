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
            \App\Handler\PingHandler::class => \App\Handler\PingHandler::class,
        ],
        // Use 'factories' for services provided by callbacks/factory classes.
        'factories'  => [
            // Fully\Qualified\ClassName::class => Fully\Qualified\FactoryName::class,

            // connection
            \Doctrine\DBAL\Connection::class => \App\Core\Factory\RelationalManagerFactory::class,

            // documentation
            \App\Handler\HomePageHandler::class => \App\Handler\TemplateRendererFactory::class,
            \App\Handler\VersionsPageHandler::class => \App\Handler\TemplateRendererFactory::class,
            \App\Handler\VersionPageHandler::class => \App\Handler\TemplateRendererFactory::class,
            \App\Handler\ContactPageHandler::class => \App\Handler\TemplateRendererFactory::class,
            \App\Handler\AboutPageHandler::class => \App\Handler\TemplateRendererFactory::class,
            \App\Handler\Middleware\ContactPostHandler::class => \App\Handler\Middleware\ContactPostHandlerFactory::class,
            \App\Handler\Middleware\InputFilter\ContactInputFilter::class => \App\Core\Middleware\InputFilter\InputFilterFactory::class,

            /**
             * bussiness | my domain
             */

            // actions | users
            \App\Domain\Handler\User\Auth::class => \App\Core\Factory\AuthHandlerFactory::class,
            \App\Domain\Handler\User\Logout::class => \App\Core\Factory\LogsHandlerFactory::class,
            \App\Domain\Handler\User\Timeout::class => \App\Core\Factory\LogsHandlerFactory::class,
            \App\Domain\Handler\User\RevokeJwt::class => \App\Core\Factory\LogsHandlerFactory::class,
            \App\Domain\Handler\User\ForgotPassword::class => \App\Core\Factory\ForgotPasswordFactory::class,
            \App\Domain\Handler\User\ChangePassword::class => \App\Core\Factory\ChangePasswordFactory::class,
            \App\Domain\Handler\User\Enable::class => \App\Core\Factory\UsersHandlerFactory::class,
            \App\Domain\Handler\User\Disable::class => \App\Core\Factory\UsersHandlerFactory::class,
            \App\Domain\Handler\User\Create::class => \App\Core\Factory\UsersHandlerFactory::class,
            \App\Domain\Handler\User\GetAll::class => \App\Core\Factory\UsersHandlerFactory::class,
            \App\Domain\Handler\User\Get::class => \App\Core\Factory\UsersHandlerFactory::class,
            \App\Domain\Handler\User\Patch::class => \App\Core\Factory\UsersHandlerFactory::class,
            \App\Domain\Handler\User\Put::class => \App\Core\Factory\UsersHandlerFactory::class,
            \App\Domain\Handler\User\Delete::class => \App\Core\Factory\UsersHandlerFactory::class,
            \App\Domain\Handler\User\Metadata::class => \App\Core\Factory\UsersHandlerFactory::class,

            // actions | css
            \App\Domain\Handler\Css\Create::class => \App\Core\Factory\CssAndUsersHandlerFactory::class,
            \App\Domain\Handler\Css\Approve::class => \App\Core\Factory\CssAndUsersHandlerFactory::class,
            \App\Domain\Handler\Css\GetAll::class => \App\Core\Factory\CssHandlerFactory::class,
            \App\Domain\Handler\Css\Get::class => \App\Core\Factory\CssHandlerFactory::class,
            \App\Domain\Handler\Css\LinksEmbedded::class => \App\Core\Factory\LinksEmbeddedFactory::class,

            // services
            \App\Domain\Service\UsersServiceInterface::class => \App\Core\Domain\Service\UsersServiceFactory::class,
            \App\Domain\Service\CssServiceInterface::class => \App\Core\Domain\Service\CssServiceFactory::class,
            \App\Domain\Service\LogsServiceInterface::class => \App\Core\Domain\Service\LogsServiceFactory::class,

            // repositories
            \App\Infrastructure\Repository\Users::class => \App\Core\Infrastructure\Repository\SqlRepositoryFactory::class,
            \App\Infrastructure\Repository\Css::class => \App\Core\Infrastructure\Repository\SqlRepositoryFactory::class,
            \App\Infrastructure\Repository\Logs::class => \App\Core\Infrastructure\Repository\SqlRepositoryFactory::class,

            // middlewares
            \App\Middleware\CheckBlacklist::class => \App\Core\Middleware\JtiAuthenticationFactory::class,
            \App\Middleware\Authorization::class => \App\Core\Middleware\AuthorizationFactory::class,
            \App\Middleware\XmlFormatter::class => \App\Core\Middleware\TemplateResponseFactory::class,
            \App\Middleware\HtmlFormatter::class => \App\Core\Middleware\TemplateResponseFactory::class,
            \App\Middleware\SendMail::class => \App\Core\Middleware\SendMailFactory::class,
            \App\Middleware\CacheMiddleware::class => \App\Core\Middleware\CacheMiddlewareFactory::class,
            \Middlewares\HttpAuthentication::class => \App\Core\Middleware\JwtAuthenticationFactory::class,
            \Middlewares\AccessLog::class => \App\Core\Middleware\AccessLogFactory::class,

            // filters
            \App\Middleware\InputFilter\UserInputFilter::class => \App\Core\Middleware\InputFilter\InputFilterFactory::class,
            \App\Middleware\InputFilter\LoginInputFilter::class => \App\Core\Middleware\InputFilter\InputFilterFactory::class,
            \App\Middleware\InputFilter\EmailInputFilter::class => \App\Core\Middleware\InputFilter\InputFilterFactory::class,
            \App\Middleware\InputFilter\PasswordInputFilter::class => \App\Core\Middleware\InputFilter\InputFilterFactory::class,
            \App\Middleware\InputFilter\CssInputFilter::class => \App\Core\Middleware\InputFilter\InputFilterFactory::class,
            \App\Middleware\InputFilter\NameAndEmailInputFilter::class => \App\Core\Middleware\InputFilter\InputFilterFactory::class,
        ],
    ],
];
