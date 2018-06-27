<?php

declare(strict_types=1);

namespace App\Domain\Service\Exception;

final class InvalidStatusException extends \DomainException implements ExceptionInterface
{
    public static function approve(): self
    {
        return new self(sprintf('Invalid status exception for approve()'));
    }
    
    public static function enable(string $status): self
    {
        return new self(sprintf('Invalid status exception for enable. Current status "%s"', $status));
    }
}
