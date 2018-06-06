<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\User;
use App\Infrastructure\Repository\Users;
use App\Domain\Service\Exception\UserNotFoundException;
use App\Domain\Service\Exception\UserEmailExistsException;

final class UsersService implements UsersServiceInterface
{
    /**
     * @var Users
     */
    private $users;

    public function __construct(Users $users)
    {
        $this->users = $users;
    }

    public function register(string $name, string $email, string $password, bool $admin): int
    {
        try {
            if ( ! $admin) {
                return $this->users->add(User::new(null, $name, $email, $password));
            } else {
                return $this->users->add(User::newAdmin(null, $name, $email, $password));
            }
        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            throw UserEmailExistsException::fromUserEmail($email);
        }
    }

    public function getAll(): array
    {
        return $this->users->all();
    }

    public function getById(int $id): User
    {
        $user = $this->users->findById($id);

        if ( ! $user instanceof User) {
            throw UserNotFoundException::fromUserId($id);
        }

        return $user;
    }

    public function getByEmail(string $email): User
    {
        $user = $this->users->findByEmail($email);

        if ( ! $user instanceof User) {
            throw UserNotFoundException::fromUserEmail($email);
        }

        return $user;
    }

    public function editPartial(int $id, array $data): int
    {
        try {
            return $this->users->editPartial($id, $data);
        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            throw UserEmailExistsException::fromUserEmail($data['email']);
        }
    }

    public function edit(int $id, array $data): int
    {
        try {
            if ( ! $data['admin']) {
                return $this->users->edit(User::new($id, $data['name'], $data['email'], $data['password']));
            } else {
                return $this->users->edit(User::newAdmin($id, $data['name'], $data['email'], $data['password']));
            }
        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            throw UserEmailExistsException::fromUserEmail($data['email']);
        }
    }

    public function delete(int $id): int
    {
        return $this->users->remove($id);
    }
}
