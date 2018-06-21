<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Css;
use App\Domain\Value\Author;
use App\Domain\Value\Tag;
use App\Infrastructure\Repository\Css as CssS;
use App\Domain\Service\Exception\StyleExistsException;

final class CssService implements CssServiceInterface
{
    /**
     * @var Css
     */
    private $css;

    public function __construct(CssS $css)
    {
        $this->css = $css;
    }

    public function register(string $name, string $description, string $style, Author $author, ?Tag $tag): int
    {
        try {
            return $this->css->add(Css::new(null, $name, $description, $style, $author, $tag));
        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            throw StyleExistsException::fromName($name);
        }
    }

    public function getAll(): array
    {
        throw new \Exception('Method getAll() is not implemented.');
    }

    public function getById(int $id): ?Css
    {
        throw new \Exception('Method getById() is not implemented.');
    }

    public function editPartial(int $id, array $data): int
    {
        throw new \Exception('Method getById() is not implemented.');
    }

    public function edit(int $id, array $data): int
    {
        throw new \Exception('Method edit() is not implemented.');
    }

    public function delete(int $id): int
    {
        throw new \Exception('Method delete() is not implemented.');
    }
}
