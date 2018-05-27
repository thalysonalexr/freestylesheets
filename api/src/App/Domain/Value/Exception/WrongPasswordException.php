<?php

declare(strict_types=1);

namespace App\Domain\Value\Exception;

final class WrongPasswordException extends \InvalidArgumentException implements ExceptionInterface
{
    public static function fromWrongPassword(string $password): self
    {
        return new self(sprintf('Password "%s" is incorrect', (string) $password));
    }
}
