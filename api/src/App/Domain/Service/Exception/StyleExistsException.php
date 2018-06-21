<?php

declare(strict_types=1);

namespace App\Domain\Service\Exception;

final class StyleExistsException extends \DomainException implements ExceptionInterface
{
    public static function fromName(string $name): self
    {
        return new self(sprintf('Style with name "%s" already exists', (string) $name));
    }
}
