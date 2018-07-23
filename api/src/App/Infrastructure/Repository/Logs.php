<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Log;
use App\Domain\Value\Jti;

interface Logs
{
    public function add(Log $log): int;

    public function logout(int $idUser, Jti $jti): bool;

    public function timeout(int $idUser, Jti $jti): bool;

    public function checkTokenInBlacklist(Jti $jti): bool;

    public function revokeToken(Jti $jti): bool;
}
