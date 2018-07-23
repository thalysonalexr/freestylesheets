<?php

declare(strict_types=1);

namespace App\Domain\Value;

use App\Domain\Entity\User;

final class PasswordRecovery implements \JsonSerializable
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var Jti
     */
    private $jti;
    /**
     * @var string
     */
    private $latest;
    /**
     * @var User
     */
    private $user;

    public function getId(): int
    {
        return $this->id;
    }

    public function getJti(): Jti
    {
        return $this->jti;
    }

    public function setJti(Jti $jti): void
    {
        $this->jti = $jti;
    }

    public function getLatest(): string
    {
        return $this->latest;
    }

    public function setLatest(string $latest): void
    {
        $this->latest = $latest;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function __construct(?int $id, Jti $jti, string $latest, User $user)
    {
        $this->id = $id;
        $this->jti = $jti;
        $this->latest = $latest;
        $this->user = $user;
    }

    public static function fromNativeData(?int $id, Jti $jti, string $latest, User $user): self
    {
        return new self($id, $jti, $latest, $user);
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'jti' => $this->jti->getValue(),
            'latest' => $this->latest,
            'user' => $this->user->jsonSerialize()
        ];
    }
}
