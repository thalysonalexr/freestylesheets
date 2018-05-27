<?php

namespace App\Core\Crud;

use App\Domain\Service\UsersServiceInterface;

interface CrudInterface
{
    public function __construct(UsersServiceInterface $usersService);
}
