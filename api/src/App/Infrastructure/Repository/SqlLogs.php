<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use Doctrine\DBAL\Connection;
use App\Domain\Entity\Log;

final class SqlLogs implements Logs
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function add(Log $log): int
    {
        $this->connection->executeUpdate(
            'INSERT INTO LOGS (id_user, signin_dt, signout_dt, status, timeout, ip, browser) '.
            'VALUES (:id_user, :signin_dt, :signout_dt, :status, :timeout, :ip, :browser) ',
            [
                'id_user' => $log->getIdUser(),
                'signin_dt' => $log->getSignInDt(),
                'signout_dt' => $log->getSignOutDt(),
                'status' => $log->getStatus() ? 1 : 0,
                'timeout' => $log->getTimeout() ? 1 : 0,
                'ip' => $log->getIp(),
                'browser' => $log->getBrowser()
            ]
        );

        return (int) $this->connection->lastInsertId();
    }

    public function logout(int $idUser, string $jti): int
    {
        $update = $this->connection->executeUpdate(
            'UPDATE LOGS SET signout_dt = :signout_dt WHERE id = :id',
            [
                'id' => $idUser,
                'signout_dt' => (new \DateTime())->format('Y-m-d H:i:s')
            ]
        );

        // revoke token by jti
        $create = $this->connection->executeUpdate(
            'INSERT INTO BLACKLIST (jti) VALUES (:jti)',
            ['jti' => $jti]
        );

        return ($create && $update) ? 1 : 0;
    }

    public function timeout(int $idUser, string $jti): int
    {
        $update = $this->connection->executeUpdate(
            'UPDATE LOGS SET timeout = :timeout, signout_dt = :signout_dt WHERE id = :id',
            [
                'id' => $idUser,
                'timeout' => 1,
                'signout_dt' => (new \DateTime())->format('Y-m-d H:i:s')
            ]
        );

        // revoke token by jti
        $create = $this->connection->executeUpdate(
            'INSERT INTO BLACKLIST (jti) VALUES (:jti)',
            ['jti' => $jti]
        );

        return ($create && $update) ? 1 : 0;
    }

    public function checkTokenInBlacklist(string $jti): bool
    {
        $jti = $this->connection->executeQuery(
            'SELECT jti FROM BLACKLIST WHERE jti = :jti',
            ['jti' => $jti]
        );

        return $jti->fetch() ? true : false;
    }
}
