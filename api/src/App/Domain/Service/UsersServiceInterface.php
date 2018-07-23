<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\User;
use App\Domain\Value\PasswordRecovery;

interface UsersServiceInterface
{
    public function register(string $name, string $email, string $password, bool $admin): int;

    public function getAll(): array;

    public function getById(int $id): ?User;

    public function getByEmail(string $email): ?User;

    public function editPartial(int $id, array $data): int;

    public function edit(int $id, array $data): int;

    public function enable(User $user): bool;

    public function disable(User $user): bool;

    public function delete(int $id): int;

    public function updatePassword(int $id, array $newPassword): int;

    public function recovery(PasswordRecovery $recovery): bool;

    public function checkMaxRequireChangePassword(int $idUser, int $maxDays, int $maxRequests): bool;
}
