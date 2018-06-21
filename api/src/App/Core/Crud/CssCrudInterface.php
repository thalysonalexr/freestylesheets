<?php

declare(strict_types=1);

namespace App\Core\Crud;

use App\Domain\Service\CssServiceInterface;
use App\Domain\Service\UsersServiceInterface;
use App\Domain\Service\LogsServiceInterface;

interface CssCrudInterface
{
    public function __construct(CssServiceInterface $cssService, UsersServiceInterface $usersService, LogsServiceInterface $logsService);
}
