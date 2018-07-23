<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Css;
use App\Domain\Entity\User;
use App\Domain\Value\Author;
use App\Domain\Value\Tag;

interface CssServiceInterface
{
    /**
     * Register a new style
     *
     * @param string $name              complete name for style
     * @param string $description       description of style
     * @param string $style             style in format css3
     * @param Author $author            object author who registered style
     * @param ?Tag $tag                 tag in html5 if a style for a specific tag
     *
     * @return int                      new id genarated
     */
    public function register(string $name, string $description, string $style, Author $author, ?Tag $tag): int;
    /**
     * Get all styles
     *
     * @param array $filters            filter results by id, name or params limit, offset and order
     *
     * @return array[Css]               array of object Css or array empty
     */
    public function getAll(array $filters = []): array;
    /**
     * Get specified style by id
     *
     * @param int $id                   id unique of style
     *
     * @return ?Css                     Object Css or nullable
     */
    public function getById(int $id): ?Css;
    /**
     * Get specified style by id if approved
     *
     * @param int $id                   id unique of style
     *
     * @return ?Css                     Object Css or nullable
     */
    public function getByIdApproved(int $id): ?Css;
    /**
     * Approve style
     *
     * @param Css $style                Style to approved
     * @param User $user                User who approved style
     *
     * @return bool                     True if approved, false if not approved or already approved
     */
    public function approve(Css $style, User $user): bool;

    public function editPartial(int $id, array $data): int;

    public function edit(int $id, array $data): int;

    public function delete(int $id): int;
}
