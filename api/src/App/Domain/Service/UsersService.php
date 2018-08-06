<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\User;
use App\Domain\Value\Password;
use App\Domain\Value\PasswordRecovery;
use App\Domain\Service\Exception\InvalidStatusException;
use App\Domain\Service\Exception\UserNotFoundException;
use App\Domain\Service\Exception\UserEmailExistsException;
use App\Domain\Service\Exception\MaxChangeRecoveryPasswordException;
use App\Infrastructure\Repository\Users;
use App\Infrastructure\Repository\Exception\ManyValuesException;

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

    public function editPartial(int $id, array $data): bool
    {
        if (count($data) !== 1) {
            throw ManyValuesException::message();
        }

        try {
            return $this->users->editPartial($id, $data);
        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            throw UserEmailExistsException::fromUserEmail($data['email']);
        }
    }

    public function edit(int $id, array $data): bool
    {
        try {
            if ( ! $data['admin']) {
                return $this->users->edit(User::new($id, $data['name'], $data['email'], null));
            } else {
                return $this->users->edit(User::newAdmin($id, $data['name'], $data['email'], null));
            }
        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            throw UserEmailExistsException::fromUserEmail($data['email']);
        }
    }

    public function enable(User $user): bool
    {
        if ($user->isActive() === User::ACTIVE) {
            throw InvalidStatusException::enable('active');
        }

        $user->enable();

        return $this->users->enableOrDisableUser($user);
    }

    public function disable(User $user): bool
    {
        if ($user->isActive() === User::INACTIVE) {
            throw InvalidStatusException::enable('inactive');
        }

        $user->disable();

        return $this->users->enableOrDisableUser($user);
    }

    public function delete(int $id): int
    {
        return $this->users->remove($id);
    }

    public function updatePassword(int $id, array $newPassword): int
    {
        $newPassword['password'] = Password::hash($newPassword['password']);

        return $this->users->editPartial($id, $newPassword);
    }

    public function getTotal(): int
    {
        return $this->users->count();
    }

    public function getTotalAdmin(): int
    {
        return $this->users->count(['admin' => 1]);
    }

    public function getTotalActives(): int
    {
        return $this->users->count(['status' => 1]);
    }

    public function recovery(PasswordRecovery $recovery): bool
    {
        return $this->users->registerRecoveryPassword($recovery);
    }

    public function checkMaxRequireChangePassword(int $idUser, int $maxDays, int $maxRequests): bool
    {
        if ($this->users->checkMaxRequireChangePassword($idUser, $maxDays) >= $maxRequests) {
            throw MaxChangeRecoveryPasswordException::message($maxRequests, $maxDays);
        }

        return false;
    }
}
