<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Value\CssHistory;
use App\Domain\Entity\Css as CssEntity;
use App\Domain\Entity\User as UserEntity;

interface Css
{
    public function add(CssEntity $css): int;

    public function all(array $filters = []): array;

    public function findById(int $id): ?CssEntity;

    public function approveStyle(CssHistory $history): bool;

    public function edit(CssEntity $user): int;

    public function editPartial(int $id, array $data): int;

    public function remove(int $id): int;
}
