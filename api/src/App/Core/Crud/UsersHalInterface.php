<?php

declare(strict_types=1);

namespace App\Core\Crud;

use App\Domain\Service\UsersServiceInterface;
use Zend\Expressive\Hal\ResourceGenerator;
use Zend\Expressive\Hal\HalResponseFactory;

interface UsersHalInterface
{
    /**
     * Service interface for users inject by constructor
     *
     * @param UsersServiceInterface $usersService
     * @param ResourceGenerator $resourceGenerator
     * @param HalResponseFactory $responseFactory
     */
    public function __construct(
        UsersServiceInterface $usersService,
        ResourceGenerator $resourceGenerator,
        HalResponseFactory $responseFactory
    );
}
