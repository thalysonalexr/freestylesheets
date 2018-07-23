<?php

declare(strict_types=1);

namespace App\Domain\Value;

class LinkClass implements \JsonSerializable
{
    /**
     * @var array
     */
    protected $links;

    public function getLink(string $description): ?string
    {
        return $this->links[$description];
    }

    public function getAllLinks(): array
    {
        return $this->links;
    }

    public function setLink(string $description, string $link): void
    {
        $this->links[$description] = $link;
    }

    public function __construct(array $links)
    {
        $this->links = $links;
    }

    public static function newLink(array $links): self
    {
        return new self($links);
    }

    public function jsonSerialize(): ?array
    {
        return $this->links;
    }
}
