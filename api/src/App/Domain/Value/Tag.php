<?php

declare(strict_types=1);

namespace App\Domain\Value;

final class Tag implements \JsonSerializable
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $element;
    /**
     * @var string
     */
    private $description;
    /**
     * @var Category
     */
    private $category;

    public function getId(): int
    {
        return $this->id;
    }

    public function getElement(): string
    {
        return $this->element;
    }

    public function setElement(string $element): void
    {
        $this->element = $element;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }

    public function __construct(
        ?int $id,
        string $element,
        string $description,
        Category $category
    )
    {
        $this->id = $id;
        $this->element = $element;
        $this->description = $description;
        $this->category = $category;
    }

    public static function fromNativeData(
        ?int $id,
        string $element,
        string $description,
        ?int $categoryId,
        string $categoryName,
        string $categoryDescription
    ): self
    {
        return new self(
            $id,
            $element,
            $description,
            Category::fromNativeData(
                $categoryId,
                $categoryName,
                $categoryDescription
            )
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => (string) $this->id,
            'element' => $this->element,
            'description' => $this->description,
            'category' => $this->category->jsonSerialize()
        ];
    }
}
