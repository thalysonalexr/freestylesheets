<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Value\Tag;
use App\Domain\Value\Author;
use App\Domain\Value\Status;
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

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getStyle(): string
    {
        return $this->style;
    }

    public function setStyle(string $style): void
    {
        $this->style = $style;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getStatus(): bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): void
    {
        $this->status = $status;
    }

    public function getAuthor(): Author
    {
        return $this->author;
    }

    public function setAuthor(Author $author): void
    {
        $this->author = $author;
    }

    public function getTag(): ?Tag
    {
        return $this->tag;
    }

    public function setTag(Tag $tag): void
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
        int $userId,
        string $userName,
        string $userEmail,
        ?string $tagId,
        ?string $tagElement,
        ?string $tagDescription,
        ?string $categoryId,
        ?string $categoryName,
        ?string $categoryDescription
    ): self
    {
        $tag = $tagId === null ? null : Tag::fromNativeData((int) $tagId, $tagElement, $tagDescription, (int) $categoryId, $categoryName, $categoryDescription);

        return new self(
            $id,
            $name,
            $description,
            $style,
            $createdAt,
            $status,
            Author::fromNativeData(
                $userId,
                $userName,
                $userEmail
            ),
            $tag
        );
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
        return new self($id, $name, $description, $style, (new \DateTime())->format('Y-m-d H:i:s'), Status::NOT_APPROVED, $author, $tag);
    }

    public function isApproved(): bool
    {
        return $this->status === Status::APPROVED;
    }

    public function approve(): void
    {
        $this->status = Status::APPROVED;
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
            'tag' => $this->tag instanceof Tag ? $this->tag->jsonSerialize() : null
        ];
    }
}
