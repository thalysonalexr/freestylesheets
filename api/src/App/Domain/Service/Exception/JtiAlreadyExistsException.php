<?php

declare(strict_types=1);

namespace App\Domain\Service\Exception;

final class JtiAlreadyExistsException extends \DomainException implements ExceptionInterface
{
    public static function message(string $jti): self
    {
        return new self(sprintf('This "%s" already exists in blacklist', (string) $jti));
    }
}
