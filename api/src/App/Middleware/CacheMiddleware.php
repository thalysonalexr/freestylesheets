<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\Response\XmlResponse;
use Zend\Diactoros\Response\EmptyResponse;

use function md5;
use function file_exists;
use function time;
use function filemtime;
use function pathinfo;
use function file_get_contents;
use function file_put_contents;
use function json_decode;
use function strpos;

class CacheMiddleware implements MiddlewareInterface
{
    /**
     * @var array
     */
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        if ('GET' !== $request->getMethod()) {
            return $handler->handle($request);
        }

        $fileName = md5((string)$request->getUri());
        $file = $this->config['path'] . $fileName . '.cache';

        switch ($request->getHeaderLine('Accept')) {
            case 'application/json':
                $file .= '.json';
                break;

            case 'application/xml':
                $file .= '.xml';
                break;
        }

        $file .= self::getExtension($file);

        if ($this->config['enabled'] && file_exists($file) && (time() - filemtime($file)) < $this->config['lifetime']) {

            if ($request->getHeaderLine('If-Modified-Since')) {
                return (new EmptyResponse())
                    ->withStatus(304)
                    ->withHeaders([
                        'Last-Modified' => date('D, d M Y H:i:s T', filemtime($cacheFilePath))
                    ]);
            }

            switch (pathinfo($file, PATHINFO_EXTENSION)) {
                case 'json':
                    return new JsonResponse(json_decode(file_get_contents($file)));
                    break;
                case 'xml':
                    return new XmlResponse(file_get_contents($file));
                    break;
                case 'html':
                    return new HtmlResponse(file_get_contents($file));
                    break;
            }
        }

        $response = $handler->handle($request);

        if ($this->config['enabled']) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'cache') {
                switch (true) {
                    case $response instanceof HtmlResponse:
                        $file .= '.html';
                        break;
                    case $response instanceof JsonResponse:
                        $file .= '.json';
                        break;
                    case $response instanceof XmlResponse:
                        $file .= '.xml';
                        break;
                }
            }

            if (
                $response instanceof HtmlResponse ||
                $response instanceof JsonResponse ||
                $response instanceof XmlResponse
            ) {
                file_put_contents($file, $response->getBody());
            }
        }

        return $response;
    }

    public static function getExtension(string $file): ?string
    {
        if (file_exists($file . '.html') && !strpos($file, '.html')) {
            $ext = '.html';
        } else

        if (file_exists($file . '.json')  && !strpos($file, '.json')) {
            $ext = '.json';
        } else

        if (file_exists($file . '.xml')  && !strpos($file, '.xml')) {
            $ext = '.xml';
        }

        return $ext;
    }
}