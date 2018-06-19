<?php

declare(strict_types=1);

namespace App\Domain\Service\Exception;

final class UserNotFoundException extends \RuntimeException implements ExceptionInterface
{
    public static function fromUserId(int $id): self
    {
        return new self(sprintf('No user was found for id "%s"', (string) $id));
    }

    public static function fromUserEmail(string $email): self
    {
        return new self(sprintf('No user was found for email "%s"', (string) $email));
    }
}
