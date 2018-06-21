<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Css;
use App\Domain\Value\Author;
use App\Domain\Value\Tag;

interface CssServiceInterface
{
    public function register(string $name, string $description, string $style, Author $author, ?Tag $tag): int;

    public function getAll(): array;

    public function getById(int $id): ?Css;

    public function editPartial(int $id, array $data): int;

    public function edit(int $id, array $data): int;

    public function delete(int $id): int;
}
