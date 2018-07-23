<?php

declare(strict_types=1);

namespace App\Domain\Value\Exception;

final class InvalidJtiException extends \InvalidArgumentException implements ExceptionInterface
{
    public static function message(string $jti, int $min, int $max): self
    {
        return new self(sprintf('Jti "%s" requires must be string and length >= %s and <= %s', (string) $jti, (string) $min, (string) $max));
    }
}
