<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Exception;

final class OffsetRequireLimitException extends \RuntimeException implements ExceptionInterface
{
    public static function message(): self
    {
        return self(sprintf("The offset parameter requires the query parameter limit"));
    }
}
