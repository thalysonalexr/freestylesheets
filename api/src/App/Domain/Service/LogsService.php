<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Log;
use App\Infrastructure\Repository\Logs;
use App\Domain\Service\Exception\JtiAlreadyExistsException;

final class LogsService implements LogsServiceInterface
{
    /**
     * @var Logs
     */
    private $logs;

    public function __construct(Logs $logs)
    {
        $this->logs = $logs;
    }

    public function login(int $idUser, bool $status): int
    {
        return $this->logs->add(Log::checkin($idUser, $status));
    }

    public function logout(int $idUser, string $jti): bool
    {
        try {
            return $this->logs->logout($idUser, $jti);
        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            throw JtiAlreadyExistsException::message($jti);
        }
        
    }

    public function timeout(int $idUser, string $jti): bool
    {
        try {
            return $this->logs->timeout($idUser, $jti);
        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            throw JtiAlreadyExistsException::message($jti);
        }
    }

    public function tokenInBlacklist(string $jti): bool
    {
        return $this->logs->checkTokenInBlacklist($jti);
    }

    public function revokeJwt(string $jti): bool
    {
        try {
            return $this->logs->revokeToken($jti);
        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            throw JtiAlreadyExistsException::message($jti);
        }
    }
}
