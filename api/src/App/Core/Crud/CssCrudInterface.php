<?php

declare(strict_types=1);

namespace App\Core\Crud;

use App\Domain\Service\CssServiceInterface;

interface CssCrudInterface
{
    /**
     * Service interface for styles inject by constructor
     *
     * @param CssServiceInterface $cssService
     */
    public function __construct(
        CssServiceInterface $cssService
    );
}
