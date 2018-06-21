<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Log;

interface Logs
{
    public function add(Log $log): int;

    public function logout(int $idUser, string $jti): bool;

    public function timeout(int $idUser, string $jti): bool;

    public function checkTokenInBlacklist(string $jti): bool;

    public function revokeToken(string $jti): bool;
}
