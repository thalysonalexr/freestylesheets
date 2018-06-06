<?php

declare(strict_types=1);

namespace App\Domain\Value;

use App\Domain\Value\Exception\WrongPasswordException;

class Password
{
    /**
     * @var array
     */
    const OPTIONS = [
        'cost' => 12
    ];

    public static function hash(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT, self::OPTIONS);
    }

    public static function verify(string $password, string $hash): bool
    {
        if ( ! password_verify($password, $hash)) {
            throw WrongPasswordException::fromWrongPassword($password);
        }
        return true;
    }
}
