<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\User;

interface Users
{
    public function add(User $user): int;

    public function all(): array;

    public function findById(int $id): ?User;

    public function findByEmail(string $email): ?User;

    public function edit(User $user): bool;

    public function editPartial(int $id, array $data): bool;

    public function remove(int $id): int;
}
