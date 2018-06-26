<?php

declare(strict_types=1);

namespace App\Domain\Service\Exception;

final class StyleNotFoundException extends \RuntimeException implements ExceptionInterface
{
    public static function fromStyleId(int $id): self
    {
        return new self(sprintf('No style was found for id "%s"', (string) $id));
    }

    public static function fromStyleName(string $name): self
    {
        return new self(sprintf('No style was found for name "%s"', (string) $name));
    }
}
