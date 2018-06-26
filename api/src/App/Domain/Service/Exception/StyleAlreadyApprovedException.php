<?php

declare(strict_types=1);

namespace App\Domain\Service\Exception;

final class StyleAlreadyApprovedException extends \DomainException implements ExceptionInterface
{
    public static function fromStyleId(int $id): self
    {
        return new self(sprintf('This style has already been approved for id "%s"', (string) $id));
    }
}
