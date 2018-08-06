<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\User;
use App\Domain\Value\PasswordRecovery;

interface UsersServiceInterface
{
    /**
     * Register a new user
     * 
     * @param string $name                  name complete of user
     * @param string $email                 valid email address for login
     * @param string $password              password for user login
     * @param bool $admin                   if admin
     * 
     * @return int                          a new identifier for user created
     */
    public function register(string $name, string $email, string $password, bool $admin): int;

    /**
     * List all users registered
     * 
     * @return array[User]
     */
    public function getAll(): array;

    /**
     * Get a user by id
     * 
     * @param int $id                       identifier of user
     * @return User
     */
    public function getById(int $id): ?User;

    /**
     * Get a user by email
     * 
     * @param string $email                 valid email address of user
     * @return User
     */
    public function getByEmail(string $email): ?User;

    /**
     * Edit partial one user
     * 
     * @param int $id                       identifier of user
     * @param array $data                   only key and value
     * 
     * @return bool                         true if successfully or false for failed
     */
    public function editPartial(int $id, array $data): bool;

    /**
     * Edit entity user complete
     * 
     * @param int $id                       identifier of user
     * @param array $data                   keys and values for user
     * 
     * @return bool                         true if successfully false if failed
     */
    public function edit(int $id, array $data): bool;

    /**
     * Enable a user
     * 
     * @param User $user                    user to enable
     * @return bool
     */
    public function enable(User $user): bool;

    /**
     * Disable a user
     * 
     * @param User $user                    user to disable
     * @param bool
     */
    public function disable(User $user): bool;

    /**
     * Delete a user
     * 
     * @param int                           identifier of user
     * @return int
     */
    public function delete(int $id): int;

    /**
     * Update a password value
     * 
     * @param int $id                       identifier of user
     * @param array $newPassword            key and value of new password
     * 
     * @param int
     */
    public function updatePassword(int $id, array $newPassword): int;

    /**
     * Register in history init a recovery password
     * 
     * @param PasswordRecovery $recovery
     * @return bool
     */
    public function recovery(PasswordRecovery $recovery): bool;

    /**
     * Check if max requests in recovery password
     * 
     * @param int $idUser                       identifier of user
     * @param int $maxDays                      max interval of days
     * @param int $maxRequests                  max attempts requests recovery
     * 
     * @param bool
     */
    public function checkMaxRequireChangePassword(int $idUser, int $maxDays, int $maxRequests): bool;
}
