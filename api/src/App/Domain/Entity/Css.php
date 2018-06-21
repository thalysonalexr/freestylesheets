<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Value\Tag;
use App\Domain\Value\Author;
/**
 * @Entity @Table(name="css")
 */
final class Css implements \JsonSerializable
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $description;
    /**
     * @var string
     */
    private $style;
    /**
     * @var string
     */
    private $createdAt;
    /**
     * @var bool
     */
    private $status;
    /**
     * @var Author
     */
    private $author;
    /**
     * @var Tag
     */
    private $tag;

    public function getId(): int
    {
        return (int) $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    public function getStyle(): string
    {
        return $this->style;
    }

    public function setStyle(string $style)
    {
        $this->style = $style;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getStatus(): bool
    {
        return $this->status;
    }

    public function setStatus(bool $status)
    {
        $this->status = $status;
    }

    public function getAuthor(): Author
    {
        return $this->author;
    }

    public function setAuthor(Author $author)
    {
        $this->author = $author;
    }

    public function getTag(): ?Tag
    {
        return $this->tag;
    }

    public function setTag(Tag $tag)
    {
        $this->tag = $tag;
    }

    public function __construct(
        ?int $id,
        string $name,
        string $description,
        string $style,
        string $createdAt,
        bool $status,
        Author $author,
        ?Tag $tag
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->style = $style;
        $this->createdAt = $createdAt;
        $this->status = $status;
        $this->author = $author;
        $this->tag = $tag;
    }

    public static function fromNativeData(
        ?int $id,
        string $name,
        string $description,
        string $style,
        string $createdAt,
        bool $status,
        Author $author,
        ?Tag $tag
    ): self
    {
        return new self($id, $name, $description, $style, $createdAt, $status, $author, $tag);
    }

    public static function new(
        ?int $id,
        string $name,
        string $description,
        string $style,
        Author $author,
        ?Tag $tag
    ): self
    {
        return self::fromNativeData($id, $name, $description, $style, (new \DateTime())->format('Y-m-d H:i:s'), false, $author, $tag);
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => (string) $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'style' => $this->style,
            'created_at' => $this->createdAt,
            'status' => $this->status,
            'author' => $this->author->jsonSerialize(),
            'tag' => $this->tag->jsonSerialize()
        ];
    }
}
