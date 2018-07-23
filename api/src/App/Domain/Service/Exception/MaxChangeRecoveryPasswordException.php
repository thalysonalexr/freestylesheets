<?php

declare(strict_types=1);

namespace App\Domain\Service\Exception;

final class MaxChangeRecoveryPasswordException extends \DomainException implements ExceptionInterface
{
    public static function message(int $maxRequests, int $maxDays): self
    {
        return new self(sprintf('The limit of recovery password is "%s" in "%s" days', (string) $maxRequests, (string) $maxDays));
    }
}
