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

    public function setElement(string $element)
    {
        $this->element = $element;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function setCategory(Category $category)
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
        Category $category
    ): self
    {
        return new self($id, $element, $description, $category);
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
