<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\User;
use Doctrine\DBAL\Connection;

final class SqlUsers implements Users
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return User[]
     */
    public function all(): array
    {
        $allUsers = $this->connection->executeQuery(
            'SELECT ' .
            ' u.id, ' .
            ' u.name, ' .
            ' u.email, ' .
            ' u.password, ' .
            ' u.admin, ' .
            ' u.created_at, ' .
            ' u.status ' .
            'FROM USERS u'
        );

        return $allUsers->fetchAll(
            \PDO::FETCH_FUNC,
            [self::class, 'createUser']
        );
    }

    public function add(User $user): int
    {
        $this->connection->executeUpdate(
            'INSERT INTO USERS (name, email, password, admin, created_at, status) '.
            'VALUES (:name, :email, :password, :admin, :created_at, :status) ',
            [
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'password' => $user->getPassword(),
                'admin' => $user->isAdmin() ? 1 : 0,
                'created_at' => $user->getCreatedAt(),
                'status' => $user->isActive() ? 1 : 0
            ]
        );

        return (int) $this->connection->lastInsertId();
    }

    public function findById(int $id): ?User
    {
        $retrieveByIdStatement = $this->connection->executeQuery(
            'SELECT ' .
            ' u.id, ' .
            ' u.name, ' .
            ' u.email, ' .
            ' u.password, ' .
            ' u.admin, ' .
            ' u.created_at, ' .
            ' u.status ' .
            'FROM USERS u ' .
            'WHERE id = :id',
            [
                'id' => (string) $id
            ]
        );

        $userData = $retrieveByIdStatement->fetch(\PDO::FETCH_ASSOC);

        if ( ! $userData) {
            return null;
        }

        return $this->createUser(
            $userData['id'],
            $userData['name'],
            $userData['email'],
            $userData['password'],
            (bool) $userData['admin'],
            $userData['created_at'],
            (bool) $userData['status']
        );
    }

    public function findByEmail(string $email): ?User
    {
        $retrieveByEmailStatement = $this->connection->executeQuery(
            'SELECT ' .
            ' u.id, ' .
            ' u.name, ' .
            ' u.email, ' .
            ' u.password, ' .
            ' u.admin, ' .
            ' u.created_at, ' .
            ' u.status ' .
            'FROM USERS u ' .
            'WHERE email = :email',
            [
                'email' => (string) $email
            ]
        );

        $userData = $retrieveByEmailStatement->fetch(\PDO::FETCH_ASSOC);

        if ( ! $userData) {
            return null;
        }

        return $this->createUser(
            $userData['id'],
            $userData['name'],
            $userData['email'],
            $userData['password'],
            (bool) $userData['admin'],
            $userData['created_at'],
            (bool) $userData['status']
        );
    }

    public function edit(User $user): int
    {
        return $this->connection->executeUpdate(
            'UPDATE USERS SET name = :name, email = :email, password = :password, admin = :admin WHERE id = :id',
            [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'password' => $user->getPassword(),
                'admin' => $user->isAdmin() ? 1 : 0
            ]
        );
    }

    public function editPartial(int $id, array $data): int
    {
        $query = array_map(function($field){
            return "{$field} = :{$field}";
        }, array_keys($data));

        $data['id'] = $id;

        return $this->connection->executeUpdate(
            'UPDATE USERS SET ' .
            implode(', ', $query) .
            ' WHERE id = :id',
            $data
        );
    }

    public function remove(int $id): int
    {
        return $this->connection->executeUpdate(
            'DELETE FROM USERS WHERE id = :id',
            [
                'id' => $id
            ]
        );
    }

    public function createUser(
        string $id,
        string $name,
        string $email,
        string $password,
        bool $admin,
        string $createdAt,
        bool $status
    ) {
        return User::fromNativeData(
            (int) $id,
            $name,
            $email,
            $password,
            $admin,
            $createdAt,
            $status
        );
    }
}
