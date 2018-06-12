<?php

declare(strict_types=1);

namespace App\Core\Crud;

use App\Domain\Service\CssServiceInterface;

interface CssCrudInterface
{
    public function __construct(CssServiceInterface $cssService);
}
