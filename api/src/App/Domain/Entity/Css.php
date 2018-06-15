<?php

declare(strict_types=1);

namespace App\Domain\Entity;
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
     * @var int
     */
    private $idUser;
    /**
     * @var int
     */
    private $idElement;

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

    public function getIdUser(): int
    {
        return $this->idUser;
    }

    public function setIdUser(int $idUser)
    {
        $this->idUser = $idUser;
    }

    public function getIdElement(): int
    {
        return $this->idElement;
    }

    public function setIdElement(int $idElement)
    {
        $this->idElement = $idElement;
    }

    public function __construct(
        ?int $id,
        string $name,
        string $description,
        string $style,
        string $createdAt,
        bool $status,
        int $idUser,
        int $idElement
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->style = $style;
        $this->createdAt = $createdAt;
        $this->status = $status;
        $this->idUser = $idUser;
        $this->idElement = $idElement;
    }

    public static function new(
        ?int $id,
        string $name,
        string $description,
        string $style,
        int $idUser,
        int $idElement
    ): self
    {
        return new self($id, $name, $description, $style, (new \DateTime())->format('Y-m-d H:i:s'), false, $idUser, $idElement);
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
            'id_user' => $this->idUser,
            'id_element' => $this->idElement
        ];
    }
}
