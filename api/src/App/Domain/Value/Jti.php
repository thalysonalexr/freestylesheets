<?php

declare(strict_types=1);

namespace App\Domain\Value;

use App\Domain\Value\Exception\InvalidJtiException;

use function is_string;

final class Jti implements \JsonSerializable
{
    /**
     * @var int
     */
    const MIN_LENGTH = 16;
    /**
     * @var int
     */
    const MAX_LENGTH = 32;
    /**
     * @var string
     */
    private $jti;

    public function getValue(): string
    {
        return $this->jti;
    }

    public function __construct(string $jti)
    {
        if (is_string($jti) && strlen($jti) >= self::MIN_LENGTH && strlen($jti) <= self::MAX_LENGTH) {
            $this->jti = $jti;
        } else {
            throw InvalidJtiException::message($jti, self::LENGTH);
        }
    }

    public static function new(string $jti): self
    {
        return new self($jti);
    }

    public function jsonSerialize(): array
    {
        return [
            'jti' => $this->jti
        ];
    }
}
