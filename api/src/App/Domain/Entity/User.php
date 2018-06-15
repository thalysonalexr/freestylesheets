<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Value\Password;
/**
 * @Entity @Table(name="users")
 */
final class User implements \JsonSerializable
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $email;
    /**
     * @var Password
     */
    private $password;
    /**
     * @var bool
     */
    private $admin;
    /**
     * @var string
     */
    private $createdAt;
    /**
     * @var bool
     */
    private $status;

    public function getId(): int
    {
        return (int) $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    public function getAdmin(): bool
    {
        return $this->admin;
    }

    public function setAdmin(bool $admin)
    {
        $this->admin = $admin;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getStatus(): bool
    {
        return $this->status;
    }

    public function setStatus(bool $status)
    {
        $this->status = $status;
    }

    public function __construct(
        ?int $id,
        string $name,
        string $email,
        string $password,
        bool $admin,
        string $createdAt,
        bool $status
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->admin = $admin;
        $this->createdAt = $createdAt;
        $this->status = $status;
    }

    public static function fromNativeData(
        ?int $id,
        string $name,
        string $email,
        string $password,
        bool $admin,
        string $createdAt,
        bool $status
    ): self
    {
        return new self(
            $id,
            $name,
            $email,
            $password,
            $admin,
            $createdAt,
            $status
        );
    }

    public static function new(
        ?int $id,
        string $name,
        string $email,
        string $password
    ): self
    {
        return new self($id, $name, $email, Password::hash($password), false, (new \DateTime())->format('Y-m-d H:i:s'), true);
    }

    public static function newAdmin(
        ?int $id,
        string $name,
        string $email,
        string $password
    ): self
    {
        return new self($id, $name, $email, Password::hash($password), true, (new \DateTime())->format('Y-m-d H:i:s'), true);
    }

    public function isAdmin(): bool
    {
        return $this->admin;
    }

    public function isActive(): bool
    {
        return $this->status;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => (string) $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'admin' => $this->admin,
            'created_at' => $this->createdAt,
            'status' => $this->status
        ];
    }
}
