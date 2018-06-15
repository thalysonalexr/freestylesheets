<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use Doctrine\DBAL\Connection;
use App\Domain\Entity\Css as CssEntity;

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
        $this->connection->executeUpdate(
            'INSERT INTO CSS (name, description, style, created_at, status, id_user, id_element) '.
            'VALUES (:name, :description, :style, :created_at, :status, :id_user, :id_element) ',
            [
                'name' => $css->getName(),
                'description' => $css->getDescription(),
                'style' => $css->getStyle(),
                'created_at' => $css->getCreatedAt(),
                'status' => $css->getStatus() ? 1 : 0,
                'id_user' => $css->getIdUser(),
                'id_element' => $css->getIdElement()
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
}
