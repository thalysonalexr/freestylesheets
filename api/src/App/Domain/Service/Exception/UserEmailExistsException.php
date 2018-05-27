<?php

declare(strict_types=1);

namespace App\Domain\Service\Exception;

final class UserEmailExistsException extends \DomainException implements ExceptionInterface
{
    public static function fromUserEmail(string $email): self
    {
        return new self(sprintf('This e-mail "%s" already exists', (string) $email));
    }
}
