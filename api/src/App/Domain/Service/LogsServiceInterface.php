<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Value\Jti;

interface LogsServiceInterface
{
    /**
     * Register a new login
     *
     * @param int $idUser           identifier of user
     * @param bool $status          true if status successfully or false case failed
     *
     * @return int                  id of register
     */
    public function login(int $idUser, bool $status): int;
    
    /**
     * Update logger with status of logout
     *
     * @param int $idUser           identifier of user
     * @param Jti $jti              identifier of token
     *
     * @return bool                 true if successfully or false case failed
     */
    public function logout(int $idUser, Jti $jti): bool;
    
    /**
     * Update logger with status of timeout
     *
     * @param int $idUser           identifier of user
     * @param Jti $jti              identifier of token
     *
     * @return bool                 true if successfully or false case failed
     */
    public function timeout(int $idUser, Jti $jti): bool;
    
    /**
     * Check if token already exists in blacklist
     *
     * @param Jti $jti              identifier of token
     *
     * @return bool                 true if successfully or false case failed
     */
    public function tokenInBlacklist(Jti $jti): bool;
    
    /**
     * Throw identifier of token, jti, in blacklist
     *
     * @param Jti $jti              identifier of token
     *
     * @return bool                 true if successfully or false case failed
     */
    public function revokeJwt(Jti $jti): bool;
}
