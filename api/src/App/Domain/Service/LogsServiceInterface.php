<?php

declare(strict_types=1);

namespace App\Domain\Service;

interface LogsServiceInterface
{
    public function login(int $idUser, bool $status): int;

    public function logout(int $idUser, string $jti): bool;

    public function timeout(int $idUser, string $jti): bool;

    public function tokenInBlacklist(string $jti): bool;

    public function revokeJwt(string $jti): bool;
}
