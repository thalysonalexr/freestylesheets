<?php

declare(strict_types=1);

namespace App\Domain\Handler\Css;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Domain\Service\CssServiceInterface;
use App\Middleware\TemplateFormatterInterface;
use Zend\Expressive\Router\RouterInterface;

final class LinksEmbedded implements MiddlewareInterface
{
    /**
     * @var string
     */
    const EXTENSION = '.css';
    /**
     * @var CssServiceInterface
     */
    private $cssService;
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(CssServiceInterface $cssService, RouterInterface $router)
    {
        $this->cssService = $cssService;
        $this->router = $router;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $filters = $request->getQueryParams();
        $server = $request->getServerParams();

        // if change port secure 443, change here
        $protocol = $server['SERVER_PORT'] === '443' ? 'https://' : 'http://';
        $link = $protocol . $server['HTTP_HOST'];

        $css['styles_embedded'] = $this->setLinkDownload($this->cssService->getAll($filters), $link);

        return $handler->handle($request->withAttribute(TemplateFormatterInterface::class, $css));
    }

    public function setLinkDownload(array $data, string $server): array
    {
        $newData = [];

        array_map(function(\App\Domain\Entity\Css $object) use ($server, &$newData) {
            $object->setLink('download', $server . $this->router->generateUri('css.download.get', [
                'id' => $object->getId() . self::EXTENSION
            ]));

            $newData[] = $object;
        }, $data);

        return $newData;
    }
}
