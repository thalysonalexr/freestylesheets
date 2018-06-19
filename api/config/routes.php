<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Zend\Expressive\Application;
use Zend\Expressive\MiddlewareFactory;

/**
 * Setup routes with a single request method:
 *
 * $app->get('/', App\Handler\HomePageHandler::class, 'home');
 * $app->post('/album', App\Handler\AlbumCreateHandler::class, 'album.create');
 * $app->put('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.put');
 * $app->patch('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.patch');
 * $app->delete('/album/:id', App\Handler\AlbumDeleteHandler::class, 'album.delete');
 *
 * Or with multiple request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class, ['GET', 'POST', ...], 'contact');
 *
 * Or handling all request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class)->setName('contact');
 *
 * or:
 *
 * $app->route(
 *     '/contact',
 *     App\Handler\ContactHandler::class,
 *     Zend\Expressive\Router\Route::HTTP_METHOD_ANY,
 *     'contact'
 * );
 */
return function (Application $app, MiddlewareFactory $factory, ContainerInterface $container) : void {
    
    // documentation
    $app->get('/', \App\Handler\HomePageHandler::class, 'home');

    $app->get('/api/versions', \App\Handler\VersionsPageHandler::class, 'versions');

    $app->get('/api/versions/{version}', \App\Handler\VersionPageHandler::class, 'version');

    $app->get('/api/about', \App\Handler\AboutPageHandler::class, 'about');

    $app->get('/api/contact', \App\Handler\ContactPageHandler::class, 'contact');

    $app->post('/api/contact', [
        \App\Handler\Middleware\InputFilter\ContactInputFilter::class,
        \App\Handler\Middleware\ContactPostHandler::class
    ], 'contact.post');

    // test
    $app->get('/api/ping', \App\Handler\PingHandler::class, 'api.ping');

    // users
    $app->post('/api/v1/users', [
        \App\Middleware\InputFilter\UserInputFilter::class,
        \App\Domain\Handler\User\Create::class
    ], 'user.post');

    $app->post('/api/v1/users/login', [
        \App\Middleware\InputFilter\LoginInputFilter::class,
        \App\Domain\Handler\User\Auth::class
    ], 'user.auth.post');

    $app->post('/api/v1/users/timeout', [
        \App\Domain\Handler\User\Timeout::class
    ], 'user.timeout.post');

    $app->post('/api/v1/users/logout', [
        \App\Domain\Handler\User\Logout::class
    ], 'user.logout.post');

    $app->post('/api/v1/users/change-password', [
        \App\Middleware\InputFilter\PasswordInputFilter::class,
        \App\Domain\Handler\User\ChangePassword::class
    ], 'user.change-password.post');

    $app->post('/api/v1/users/forgot-password', [
        \App\Middleware\InputFilter\EmailInputFilter::class,
        \App\Domain\Handler\User\ForgotPassword::class,
        \App\Middleware\SendMail::class
    ], 'user.forgot-password.post');

    $app->get('/api/v1/users', [
        \App\Middleware\Authentication::class,
        \App\Middleware\CheckBlacklist::class,
        \App\Middleware\Authorization::class,
        \App\Domain\Handler\User\GetAll::class,
        \App\Middleware\JsonFormatter::class,
        \App\Middleware\XmlFormatter::class,
        \App\Middleware\HtmlFormatter::class
    ], 'user.all.get');

    $app->get('/api/v1/users/{id_user}', [
        \App\Middleware\Authentication::class,
        \App\Middleware\CheckBlacklist::class,
        \App\Domain\Handler\User\Get::class,
        \App\Middleware\JsonFormatter::class,
        \App\Middleware\XmlFormatter::class,
        \App\Middleware\HtmlFormatter::class
    ], 'user.get');

    $app->patch('/api/v1/users/{id_user}', [
        \App\Middleware\Authentication::class,
        \App\Middleware\CheckBlacklist::class,
        \App\Domain\Handler\User\Patch::class
    ], 'user.patch');

    $app->put('/api/v1/users/{id_user}', [
        \App\Middleware\Authentication::class,
        \App\Middleware\CheckBlacklist::class,
        \App\Middleware\InputFilter\UserInputFilter::class,
        \App\Domain\Handler\User\Put::class
    ], 'user.put');
    
    $app->delete('/api/v1/users/{id_user}', [
        \App\Middleware\Authentication::class,
        \App\Middleware\CheckBlacklist::class,
        \App\Domain\Handler\User\Delete::class
    ], 'user.delete');

    // css
    $app->post('/api/v1/css', [
        \App\Middleware\Authentication::class,
        \App\Middleware\CheckBlacklist::class,
        \App\Domain\Handler\Css\Create::class
    ], 'css.post');

    $app->get('/api/v1/css', [
        \App\Middleware\Authentication::class,
        \App\Middleware\CheckBlacklist::class,
        \App\Middleware\Authorization::class,
        \App\Domain\Handler\Css\GetAll::class,
        \App\Middleware\JsonFormatter::class,
        \App\Middleware\XmlFormatter::class,
        \App\Middleware\HtmlFormatter::class
    ], 'css.all.get');

    $app->get('/api/v1/css/{id_style}', [
        \App\Domain\Handler\Css\Get::class,
        \App\Middleware\CssResponse::class,
        \App\Middleware\JsonFormatter::class,
        \App\Middleware\XmlFormatter::class,
        \App\Middleware\HtmlFormatter::class
    ], 'css.get');

    $app->patch('/api/v1/css/{id_style}', [
        \App\Middleware\Authentication::class,
        \App\Middleware\CheckBlacklist::class,
        \App\Domain\Handler\Css\Patch::class
    ], 'css.patch');

    $app->put('/api/v1/css/{id_style}', [
        \App\Middleware\Authentication::class,
        \App\Middleware\CheckBlacklist::class,
        \App\Domain\Handler\Css\Put::class
    ], 'css.put');

    $app->delete('/api/v1/css/{id_style}', [
        \App\Middleware\Authentication::class,
        \App\Middleware\CheckBlacklist::class,
        \App\Middleware\Authorization::class,
        \App\Domain\Handler\Css\Delete::class
    ], 'css.delete');
};
