<?php

declare(strict_types=1);

namespace App\Domain\Service\Exception;

final class StyleNotApprovedException extends \DomainException implements ExceptionInterface
{
    public static function fromStyleId(int $id): self
    {
        return new self(sprintf('Style not approved for id "%s"', (string) $id));
    }
}
