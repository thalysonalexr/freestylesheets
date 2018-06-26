<?php

declare(strict_types=1);

namespace App\Infrastructure\Exception;

final class InvalidStatusException extends \DomainException implements ExceptionInterface
{
    public static function approve(): self
    {
        return new self(sprintf('Invalid status exception for approve'));
    }
}
