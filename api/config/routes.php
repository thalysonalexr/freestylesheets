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
    $app->get('/', \App\Handler\HomePageHandler::class, 'home');
    $app->get('/api/ping', \App\Handler\PingHandler::class, 'api.ping');

    // users
    $app->post('/api/v1/users/login', App\Domain\Handler\User\Auth::class, 'user.auth.post');

    $app->post('/api/v1/users', \App\Domain\Handler\User\Create::class, 'user.post');

    $app->get('/api/v1/users', [
        \App\Middleware\Authentication::class,
        \App\Middleware\Authorization::class,
        \App\Domain\Handler\User\GetAll::class
    ], 'user.all.get');

    $app->get('/api/v1/users/{id_user}', [
        \App\Middleware\Authentication::class,
        \App\Domain\Handler\User\Get::class
    ], 'user.get');

    $app->patch('/api/v1/users/{id_user}', [
        \App\Middleware\Authentication::class,
        \App\Domain\Handler\User\Patch::class
    ], 'user.patch');

    $app->put('/api/v1/users/{id_user}', [
        \App\Middleware\Authentication::class,
        \App\Domain\Handler\User\Put::class
    ], 'user.put');
    
    $app->delete('/api/v1/users/{id_user}', [
        \App\Middleware\Authentication::class,
        \App\Domain\Handler\User\Delete::class
    ], 'user.delete');
};
