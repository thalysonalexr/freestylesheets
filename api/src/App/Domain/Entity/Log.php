<?php

declare(strict_types=1);

namespace App\Domain\Entity;
/**
 * @Entity @Table(name="users")
 */
final class Log implements \JsonSerializable
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var int
     */
    private $idUser;
    /**
     * @var string
     */
    private $signInDt;
    /**
     * @var string
     */
    private $signOutDt;
    /**
     * @var bool
     */
    private $status;
    /**
     * @var bool
     */
    private $timeout;
    /**
     * @var string
     */
    private $ip;
    /**
     * @var string
     */
    private $browser;

    public function getId(): int
    {
        return (int) $this->id;
    }

    public function getIdUser(): int
    {
        return $this->idUser;
    }

    public function setIdUser(int $idUser)
    {
        $this->idUser = $idUser;
    }

    public function getSignInDt(): string
    {
        return $this->signInDt;
    }

    public function setSignInDt(string $signInDt)
    {
        $this->signInDt = $signInDt;
    }

    public function getSignOutDt(): string
    {
        return $this->signOutDt;
    }

    public function setSignOutDt(string $signOutDt)
    {
        $this->signOutDt = $signOutDt;
    }

    public function getStatus(): bool
    {
        return $this->status;
    }

    public function setStatus(bool $status)
    {
        $this->status = $status;
    }

    public function getTimeout(): bool
    {
        return $this->timeout;
    }

    public function setTimeout(bool $timeout)
    {
        $this->timeout = $timeout;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function setIp(string $ip)
    {
        $this->ip = $ip;
    }

    public function getBrowser(): string
    {
        return $this->browser;
    }

    public function setBrowser(string $browser)
    {
        $this->browser = $browser;
    }

    public function __construct(
        ?int $id,
        int $idUser,
        string $signInDt,
        ?string $signOutDt,
        bool $status,
        bool $timeout,
        string $ip,
        string $browser
    )
    {
        $this->id = $id;
        $this->idUser = $idUser;
        $this->signInDt = $signInDt;
        $this->signOutDt = $signOutDt;
        $this->status = $status;
        $this->timeout = $timeout;
        $this->ip = $ip;
        $this->browser = $browser;
    }

    public static function checkin(
        int $idUser,
        bool $status
    ): self
    {
        return new self(
            null,
            $idUser,
            (new \DateTime())->format('Y-m-d H:i:s'),
            null,
            $status,
            false,
            $_SERVER['REMOTE_ADDR'],
            $_SERVER['HTTP_USER_AGENT']
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => (string) $this->id,
            'id_user' => $this->idUser,
            'signin_dt' => $this->signInDt,
            'signout_dt' => $this->signOutDt,
            'status' => $this->status,
            'timeout' => $this->timeout,
            'ip' => $this->ip,
            'browser' => $this->browser
        ];
    }
}
