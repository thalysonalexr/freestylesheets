<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use Doctrine\DBAL\Connection;
use App\Domain\Entity\Css as CssEntity;
use App\Domain\Value\Tag;
use App\Domain\Value\Category;

final class SqlCss implements Css
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function add(CssEntity $css): int
    {
        if (null !== $css->getTag()) {
            $tagId = $this->findOrCreateTagId($css->getTag());
        }

        $this->connection->executeUpdate(
            'INSERT INTO CSS (name, description, style, created_at, status, author, tag) '.
            'VALUES (:name, :description, :style, :created_at, :status, :author, :tag) ',
            [
                'name' => $css->getName(),
                'description' => $css->getDescription(),
                'style' => $css->getStyle(),
                'created_at' => $css->getCreatedAt(),
                'status' => $css->getStatus() ? 1 : 0,
                'author' => $css->getAuthor()->getId(),
                'tag' => $tagId
            ]
        );

        return (int) $this->connection->lastInsertId();
    }

    public function all(): array
    {
        throw new \Exception('Method all() is not implemented.');
    }

    public function findById(int $id): ?CssEntity
    {
        throw new \Exception('Method findById() is not implemented.');
    }

    public function edit(CssEntity $user): int
    {
        throw new \Exception('Method findById() is not implemented.');
    }

    public function editPartial(int $id, array $data): int
    {
        throw new \Exception('Method editPartial() is not implemented.');
    }

    public function remove(int $id): int
    {
        throw new \Exception('Method remove() is not implemented.');
    }

    public function findOrCreateTagId(Tag $tag): int
    {
        $statement = $this->connection->executeQuery(
            'SELECT id FROM TAGS WHERE element = :element',
            [
                'element' => $tag->getElement()
            ]
        );

        $tagId = $statement->fetchColumn();

        if (false !== $tagId) {
            return (int) $tagId;
        }

        $categoryId = $this->findOrCreateCategoryId($tag->getCategory());

        $this->connection->executeUpdate(
            'INSERT INTO TAGS (element, description, id_category) VALUES (:element, :description, :id_category)',
            [
                'element' => $tag->getElement(),
                'description' => $tag->getDescription(),
                'id_category' => $categoryId
            ]
        );

        return (int) $this->connection->lastInsertId();
    }

    public function findOrCreateCategoryId(Category $category): int
    {
        $statement = $this->connection->executeQuery(
            'SELECT id FROM CATEGORIES WHERE name = :name',
            [
                'name' => $category->getName()
            ]
        );

        $categoryId = $statement->fetchColumn();

        if (false !== $categoryId) {
            return (int) $categoryId;
        }

        $statement = $this->connection->executeUpdate(
            'INSERT INTO CATEGORIES (name, description) VALUES (:name, :description)',
            [
                'name' => $category->getName(),
                'description' => $category->getDescription()
            ]
        );

        return (int) $this->connection->lastInsertId();
    }
}
