<?php

declare(strict_types=1);

namespace App\Domain\Value;

use App\Domain\Entity\Css;
use App\Domain\Entity\User;

final class CssHistory implements \JsonSerializable
{
    /**
     * @var Status
     */
    private $status;
    /**
     * @var string
     */
    private $dateTime;
    /**
     * @var User
     */
    private $user;
    /**
     * @var Css
     */
    private $style;

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): void
    {
        $this->status = $status;
    }

    public function getDateTime(): string
    {
        return $this->dateTime;
    }

    public function setDateTime(string $dateTime): void
    {
        $this->dateTime = $dateTime;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user =$user;
    }

    public function getStyle(): Css
    {
        return $this->style;
    }

    public function setStyle(Css $style): void
    {
        $this->style = $style;
    }

    public function __construct(
        Status $status,
        string $dateTime,
        User $user,
        Css $style
    )
    {
        $this->status = $status;
        $this->dateTime = $dateTime;
        $this->user = $user;
        $this->style = $style;
    }

    public static function fromNativeData(
        string $status,
        string $dateTime,
        ?int $userId,
        string $userName,
        string $userEmail,
        string $userPassword,
        bool $userAdmin,
        string $userCreatedAt,
        bool $userStatus,
        ?int $styleId,
        string $styleName,
        string $styleDescription,
        string $styleStyle,
        string $styleCreatedAt,
        bool $styleStatus,
        ?string $tagId,
        ?string $tagElement,
        ?string $tagDescription,
        ?string $categoryId,
        ?string $categoryName,
        ?string $categoryDescription
    ): self
    {
        return new self(
            $status,
            $dateTime,
            $user::fromNativeData(
                $userId,
                $userName,
                $userEmail,
                $userPassword,
                $userAdmin,
                $userCreatedAt,
                $userStatus
            ),
            $style::fromNativeData(
                $styleId,
                $styleName,
                $styleDescription,
                $styleStyle,
                $styleCreatedAt,
                $styleStatus,
                $userId,
                $userName,
                $userEmail,
                $tagId,
                $tagElement,
                $tagDescription,
                $categoryId,
                $categoryName,
                $categoryDescription
            )
        );
    }

    public static function newTransaction(
        Status $status,
        User $user,
        Css $style
    ): self
    {
        return new self(
            $status,
            (new \DateTime())->format('Y-m-d H:i:s'),
            $user,
            $style
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'status' => $this->status->getValue(),
            'date_time' => $this->dateTime,
            'user' => $this->user->jsonSerialize(),
            'style' => $this->style->jsonSerialize()
        ];
    }
}
