<?php

declare(strict_types=1);

namespace App\Core\Crud;

use App\Domain\Service\UsersServiceInterface;

interface UsersCrudInterface
{
    public function __construct(UsersServiceInterface $usersService);
}
