<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use Doctrine\DBAL\Connection;
use App\Domain\Entity\Css as CssEntity;
use App\Domain\Entity\User as UserEntity;
use App\Domain\Value\Tag;
use App\Domain\Value\Category;
use App\Domain\Value\Status;
use App\Domain\Value\CssHistory;
use App\Infrastructure\Exception\InvalidStatusException;

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
                'tag' => $tagId ?? null
            ]
        );

        return (int) $this->connection->lastInsertId();
    }

    public function all(array $filters = []): array
    {
        $filtersCss = new CssFilters($filters);

        $allStylesStatement = $this->connection->executeQuery(
            'SELECT ' .
            ' style.id AS style_id, ' .
            ' style.name AS style_name, ' .
            ' style.description AS style_description, ' .
            ' style.style AS style_style, ' .
            ' style.created_at AS style_createdAt, ' .
            ' style.status AS style_status, ' .
            ' user.id AS author_id, ' .
            ' user.name AS author_name, ' .
            ' user.email AS author_email, ' .
            ' tags.id AS tag_id, ' .
            ' tags.element AS tag_element, ' .
            ' tags.description AS tag_description, ' .
            ' cat.id AS category_id, ' .
            ' cat.name AS category_name, ' .
            ' cat.description AS category_description' .
            ' FROM CSS AS style ' .
            ' INNER JOIN USERS AS user ON user.id = style.author ' .
            ' LEFT JOIN TAGS AS tags ON tags.id = style.tag ' .
            ' LEFT JOIN CATEGORIES AS cat ON cat.id = tags.id_category ' .
            $filtersCss->where(),
            $filtersCss->setLike()->data()
        );

        return $allStylesStatement->fetchAll(
            \PDO::FETCH_FUNC,
            [self::class, 'createStyle']
        );
    }

    public function findById(int $id): ?CssEntity
    {
        $styleStatement = $this->connection->executeQuery(
            'SELECT ' .
            ' style.id AS style_id, ' .
            ' style.name AS style_name, ' .
            ' style.description AS style_description, ' .
            ' style.style AS style_style, ' .
            ' style.created_at AS style_createdAt, ' .
            ' style.status AS style_status, ' .
            ' user.id AS author_id, ' .
            ' user.name AS author_name, ' .
            ' user.email AS author_email, ' .
            ' tags.id AS tag_id, ' .
            ' tags.element AS tag_element, ' .
            ' tags.description AS tag_description, ' .
            ' cat.id AS category_id, ' .
            ' cat.name AS category_name, ' .
            ' cat.description AS category_description' .
            ' FROM CSS AS style ' .
            ' INNER JOIN USERS AS user ON user.id = style.author ' .
            ' LEFT JOIN TAGS AS tags ON tags.id = style.tag ' .
            ' LEFT JOIN CATEGORIES AS cat ON cat.id = tags.id_category ' .
            ' WHERE style.id = :id ' .
            ' GROUP BY style.id, style.name ',
            [
                'id' => (string) $id
            ]
        );

        $styleArray = $styleStatement->fetch(\PDO::FETCH_ASSOC);

        if ( ! $styleArray) {
            return null;
        }

        return $this->createStyle(
            $styleArray['style_id'],
            $styleArray['style_name'],
            $styleArray['style_description'],
            $styleArray['style_style'],
            $styleArray['style_createdAt'],
            (bool) $styleArray['style_status'],
            $styleArray['author_id'],
            $styleArray['author_name'],
            $styleArray['author_email'],
            $styleArray['tag_id'],
            $styleArray['tag_element'],
            $styleArray['tag_description'],
            $styleArray['category_id'],
            $styleArray['category_name'],
            $styleArray['category_description']
        );
    }

    public function approveStyle(CssHistory $transaction): bool
    {
        $style = $transaction->getStyle();

        if ($style->isApproved() !== Status::APPROVED) {
            throw InvalidStatusException::approve();
        }

        $approveStatus = (bool) $this->connection->executeUpdate(
            'UPDATE CSS SET status = :status WHERE id = :id',
            [
                'id' => (string) $style->getId(),
                'status' => $style->getStatus() ? 1 : 0
            ]
        );

        if ($approveStatus) {
            $transactionHistory = $this->initTransationHistory($transaction);
        }

        return $transactionHistory && $approveStatus;
    }

    public function initTransationHistory(CssHistory $transaction): bool
    {
        return (bool) $this->connection->executeUpdate(
            'INSERT INTO CSS_HISTORY (status, date_time, id_user, id_css) VALUES (:status, :date_time, :id_user, :id_css)',
            [
                'status' => $transaction->getStatus()->getValue() ? 1 : 0,
                'date_time' => $transaction->getDateTime(),
                'id_user' => $transaction->getUser()->getId(),
                'id_css' => $transaction->getStyle()->getId()
            ]
        );
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

    public function createStyle(
        string $id,
        string $name,
        string $description,
        string $style,
        string $createdAt,
        bool $status,
        string $userId,
        string $userName,
        string $userEmail,
        ?string $tagId,
        ?string $tagElement,
        ?string $tagDescription,
        ?string $categoryId,
        ?string $categoryName,
        ?string $categoryDescription
    ): CssEntity
    {
        return CssEntity::fromNativeData(
            (int) $id,
            $name,
            $description,
            $style,
            $createdAt,
            $status,
            (int) $userId,
            $userName,
            $userEmail,
            $tagId,
            $tagElement,
            $tagDescription,
            $categoryId,
            $categoryName,
            $categoryDescription
        );
    }
}
