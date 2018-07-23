<?php

declare(strict_types=1);

namespace App\Core\Crud;

use App\Domain\Service\UsersServiceInterface;

interface UsersCrudInterface
{
    /**
     * Service interface for users inject by constructor
     *
     * @param UsersServiceInterface $usersService
     */
    public function __construct(
        UsersServiceInterface $usersService
    );
}
