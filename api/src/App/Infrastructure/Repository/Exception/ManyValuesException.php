<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Exception;

final class ManyValuesException extends \DomainException implements ExceptionInterface
{
    public static function message(): self
    {
        return new self('The array must contain only one key (field) for the new value to be updated.');
    }
}
