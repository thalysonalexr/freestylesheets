<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Log;
use App\Domain\Value\Jti;
use App\Domain\Service\Exception\JtiAlreadyExistsException;
use App\Infrastructure\Repository\Logs;

final class LogsService implements LogsServiceInterface
{
    /**
     * @var Logs
     */
    private $logs;

    /**
     * Constructor this class
     *
     * @param Logs $logs                           log object repository
     */
    public function __construct(Logs $logs)
    {
        $this->logs = $logs;
    }

    /**
     * { @inheritdoc }
     */
    public function login(int $idUser, bool $status): int
    {
        return $this->logs->add(Log::checkin($idUser, $status));
    }

    /**
     * { @inheritdoc }
     * @throws JtiAlreadyExistsException           if jti of token already exists
     */
    public function logout(int $idUser, Jti $jti): bool
    {
        try {
            return $this->logs->logout($idUser, $jti);
        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            throw JtiAlreadyExistsException::message($jti);
        }
        
    }

    /**
     * { @inheritdoc }
     * @throws JtiAlreadyExistsException            if jti of token already exists
     */
    public function timeout(int $idUser, Jti $jti): bool
    {
        try {
            return $this->logs->timeout($idUser, $jti);
        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            throw JtiAlreadyExistsException::message($jti);
        }
    }

    /**
     * { @inheritdoc }
     */
    public function tokenInBlacklist(Jti $jti): bool
    {
        return $this->logs->checkTokenInBlacklist($jti);
    }

    /**
     * { @inheritdoc }
     * @throws JtiAlreadyExistsException            if jti of token already exists
     */
    public function revokeJwt(Jti $jti): bool
    {
        try {
            return $this->logs->revokeToken($jti);
        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            throw JtiAlreadyExistsException::message($jti);
        }
    }
}
