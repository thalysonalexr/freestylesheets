<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Css;

interface CssServiceInterface
{
    public function register(string $name, string $description, string $style, int $idUser, int $idElement): int;

    public function getAll(): array;

    public function getById(int $id): ?Css;

    public function editPartial(int $id, array $data): int;

    public function edit(int $id, array $data): int;

    public function delete(int $id): int;
}
