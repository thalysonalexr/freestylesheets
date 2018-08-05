<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Zend\Expressive\Application;
use Zend\Expressive\MiddlewareFactory;

return function (Application $app, MiddlewareFactory $factory, ContainerInterface $container) : void {

    /* Homepage */
    $app->get('/', \App\Handler\HomePageHandler::class, 'home');

    /* List all docs versions this api */
    $app->get('/api/versions', \App\Handler\VersionsPageHandler::class, 'versions');

    /* List one doc version this api */
    $app->get('/api/versions/{version}', \App\Handler\VersionPageHandler::class, 'version');

    /* Page About of project */
    $app->get('/api/about', \App\Handler\AboutPageHandler::class, 'about');

    /* Page Contact */
    $app->get('/api/contact', \App\Handler\ContactPageHandler::class, 'contact');

    /* Send Contact */
    $app->post('/api/contact', [
        \App\Handler\Middleware\InputFilter\ContactInputFilter::class,
        \App\Handler\Middleware\ContactPostHandler::class
    ], 'contact.post');

    /* Test your ping */
    $app->get('/api/ping', \App\Handler\PingHandler::class, 'api.ping');

    /* Create a new user */
    $app->post('/api/v1/users', [
        \App\Middleware\InputFilter\UserInputFilter::class,
        \App\Domain\Handler\User\Create::class
    ], 'user.post');

    /* Generate token for access */
    $app->post('/api/v1/users/login', [
        \App\Middleware\InputFilter\LoginInputFilter::class,
        \App\Domain\Handler\User\Auth::class
    ], 'user.auth.post');

    /* Set timeout */
    $app->post('/api/v1/users/timeout', \App\Domain\Handler\User\Timeout::class, 'user.timeout.post');

    /* Set logout */
    $app->post('/api/v1/users/logout', \App\Domain\Handler\User\Logout::class, 'user.logout.post');

    /* Revoke token of access */
    $app->post('/api/v1/users/revoke-token', \App\Domain\Handler\User\RevokeJwt::class, 'user.revoke.post');

    /* Enable user by id */
    $app->post('/api/v1/users/enable/{id}', [
        \Middlewares\HttpAuthentication::class,
        \App\Middleware\CheckBlacklist::class,
        \App\Middleware\Authorization::class,
        \App\Domain\Handler\User\Enable::class
    ], 'user.enable.post');

    /* Disable user by id */
    $app->post('/api/v1/users/disable/{id}', [
        \Middlewares\HttpAuthentication::class,
        \App\Middleware\CheckBlacklist::class,
        \App\Middleware\Authorization::class,
        \App\Domain\Handler\User\Disable::class
    ], 'user.disable.post');

    /* Init process recovery password */
    $app->post('/api/v1/users/forgot-password', [
        \App\Middleware\InputFilter\EmailInputFilter::class,
        \App\Domain\Handler\User\ForgotPassword::class,
        \App\Middleware\SendMail::class
    ], 'user.forgot-password.post');

    /* Change password by jwt */
    $app->post('/api/v1/users/change-password', [
        \App\Middleware\InputFilter\PasswordInputFilter::class,
        \App\Domain\Handler\User\ChangePassword::class
    ], 'user.change-password.post');

    /* Get all users */
    $app->get('/api/v1/users', [
        \App\Middleware\CacheMiddleware::class,
        \Middlewares\HttpAuthentication::class,
        \App\Middleware\CheckBlacklist::class,
        \App\Middleware\Authorization::class,
        \App\Domain\Handler\User\Metadata::class,
        \App\Domain\Handler\User\GetAll::class,
        \App\Middleware\HtmlFormatter::class
    ], 'user.all.get');

    /* Get one style by id */
    $app->get('/api/v1/users/{id}', [
        \App\Middleware\CacheMiddleware::class,
        \Middlewares\HttpAuthentication::class,
        \App\Middleware\CheckBlacklist::class,
        \App\Domain\Handler\User\Get::class,
        \App\Middleware\HtmlFormatter::class
    ], 'user.get');

    /* Edit partial style by id */
    $app->patch('/api/v1/users/{id}', [
        \Middlewares\HttpAuthentication::class,
        \App\Middleware\CheckBlacklist::class,
        \App\Middleware\InputFilter\PatchUserInputFilter::class,
        \App\Domain\Handler\User\Patch::class
    ], 'user.patch');

    /* Edit style by id */
    $app->put('/api/v1/users/{id}', [
        \Middlewares\HttpAuthentication::class,
        \App\Middleware\CheckBlacklist::class,
        \App\Middleware\InputFilter\NameAndEmailInputFilter::class,
        \App\Domain\Handler\User\Put::class
    ], 'user.put');
    
    /* Delete one user by id */
    $app->delete('/api/v1/users/{id}', [
        \Middlewares\HttpAuthentication::class,
        \App\Middleware\CheckBlacklist::class,
        \App\Domain\Handler\User\Delete::class
    ], 'user.delete');

    /* Create a new style */
    $app->post('/api/v1/css', [
        \Middlewares\HttpAuthentication::class,
        \App\Middleware\CheckBlacklist::class,
        \App\Middleware\InputFilter\CssInputFilter::class,
        \App\Domain\Handler\Css\Create::class
    ], 'css.post');

    /* Approve one style by id */
    $app->post('/api/v1/css/approve/{id}', [
        \Middlewares\HttpAuthentication::class,
        \App\Middleware\CheckBlacklist::class,
        \App\Middleware\Authorization::class,
        \App\Domain\Handler\Css\Approve::class
    ], 'css.approve.post');

    /* Get all styles */
    $app->get('/api/v1/css', [
        \App\Middleware\CacheMiddleware::class,
        \Middlewares\HttpAuthentication::class,
        \App\Middleware\CheckBlacklist::class,
        \App\Middleware\Authorization::class,
        \App\Domain\Handler\Css\GetAll::class,
        \App\Middleware\HtmlFormatter::class
    ], 'css.all.get');

    /* Get a style by id embedded */
    $app->get('/api/v1/css/embedded', [
        \Middlewares\HttpAuthentication::class,
        \App\Middleware\CheckBlacklist::class,
        \App\Domain\Handler\Css\LinksEmbedded::class,
        \App\Middleware\HtmlFormatter::class
    ], 'css.embedded.get');

    /* Direct download of style */
    $app->get('/api/v1/css/download/{id}', [
        \App\Domain\Handler\Css\Get::class,
        \App\Domain\Handler\Css\Download::class
    ], 'css.download.get');

    /* Get a style by id */
    $app->get('/api/v1/css/{id}', [
        \App\Domain\Handler\Css\Get::class,
        \App\Middleware\CssFormatter::class,
        \Middlewares\HttpAuthentication::class,
        \App\Middleware\HtmlFormatter::class
    ], 'css.get');

    /* Edit partial one style by id */
    $app->patch('/api/v1/css/{id}', [
        \Middlewares\HttpAuthentication::class,
        \App\Middleware\CheckBlacklist::class,
        \App\Domain\Handler\Css\Patch::class
    ], 'css.patch');

    /* Edit one style by id */
    $app->put('/api/v1/css/{id}', [
        \Middlewares\HttpAuthentication::class,
        \App\Middleware\CheckBlacklist::class,
        \App\Domain\Handler\Css\Put::class
    ], 'css.put');

    /* Delete one style by id */
    $app->delete('/api/v1/css/{id}', [
        \Middlewares\HttpAuthentication::class,
        \App\Middleware\CheckBlacklist::class,
        \App\Middleware\Authorization::class,
        \App\Domain\Handler\Css\Delete::class
    ], 'css.delete');
};
