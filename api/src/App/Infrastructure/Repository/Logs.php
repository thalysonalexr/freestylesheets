<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Log;

interface Logs
{
    public function add(Log $log): int;

    public function logout(int $idUser, string $jti): int;

    public function timeout(int $idUser, string $jti): int;

    public function checkTokenInBlacklist(string $jti): bool;
}
