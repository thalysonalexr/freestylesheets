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

            default:
                $file .= '.html';
                break;
        }

        $file .= self::getExtension($file);

        if ($this->config['enabled'] && file_exists($file) && (time() - filemtime($file)) < $this->config['lifetime']) {

            switch (pathinfo($file, PATHINFO_EXTENSION)) {
                case 'json':
                    $response = new JsonResponse(json_decode(file_get_contents($file)));
                    break;
                case 'xml':
                    $response = new XmlResponse(file_get_contents($file));
                    break;
                case 'html':
                    $response = new HtmlResponse(file_get_contents($file));
                    break;
            }

            $this->logger('READ', $fileName);

            return $response->withStatus(304)->withHeader(
                'Last-Modified', date('D, d M Y H:i:s T', filemtime($cacheFilePath))
            );
        }

        $response = $handler->handle($request);

        if ($this->config['enabled']) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'cache') {
                switch (true) {
                    case $response instanceof HtmlResponse:
                        file_put_contents($file . '.html', $response->getBody());
                        break;
                    case $response instanceof JsonResponse:
                        file_put_contents($file . '.json', $response->getBody());
                        break;
                    case $response instanceof XmlResponse:
                        file_put_contents($file . '.xml', $response->getBody());
                        break;
                }
            } else {
                file_put_contents($file, $response->getBody());
            }
            $this->logger('WRITE', $fileName);
        }

        return $response;
    }

    public function logger(string $mod, string $fileName)
    {
        // logger in cache
        $logger = "date [" . (new \DateTime())->format('D, d M Y H:i:s T') . "] - ";
        $logger .= "file [{$fileName}] - mod [{$mod}]";

        file_put_contents($this->config['path'] . 'logs.txt', $logger . PHP_EOL, FILE_APPEND);
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