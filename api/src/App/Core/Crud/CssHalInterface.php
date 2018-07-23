<?php

declare(strict_types=1);

namespace App\Core\Crud;

use App\Domain\Service\CssServiceInterface;
use Zend\Expressive\Hal\ResourceGenerator;
use Zend\Expressive\Hal\HalResponseFactory;

interface CssHalInterface
{
    /**
     * Service interface for styles inject by constructor
     *
     * @param CssServiceInterface $cssService
     * @param ResourceGenerator $resourceGenerator
     * @param HalResponseFactory $responseFactory
     */
    public function __construct(
        CssServiceInterface $cssService,
        ResourceGenerator $resourceGenerator,
        HalResponseFactory $responseFactory
    );
}
