<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Css;
use App\Domain\Entity\User;
use App\Domain\Value\Author;
use App\Domain\Value\Tag;
use App\Domain\Value\CssHistory;
use App\Domain\Value\Status;
use App\Infrastructure\Repository\Css as CssRepository;
use App\Domain\Service\Exception\StyleExistsException;
use App\Domain\Service\Exception\StyleNotFoundException;
use App\Domain\Service\Exception\StyleNotApprovedException;
use App\Domain\Service\Exception\StyleAlreadyApprovedException;

final class CssService implements CssServiceInterface
{
    /**
     * @var Css
     */
    private $css;

    public function __construct(CssRepository $css)
    {
        $this->css = $css;
    }

    public function register(string $name, string $description, string $style, Author $author, ?Tag $tag): int
    {
        try {
            return $this->css->add(Css::new(null, $name, $description, $style, $author, $tag));
        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            throw StyleExistsException::fromName($name);
        }
    }

    public function getAll(array $filters = []): array
    {
        return $this->css->all($filters);
    }

    public function getById(int $id): ?Css
    {
        $style = $this->css->findById($id);

        if ( ! $style instanceof Css) {
            throw StyleNotFoundException::fromStyleId($id);
        }

        return $style;
    }

    public function getByIdApproved(int $id): ?Css
    {
        $style = $this->css->findById($id);

        if ( ! $style instanceof Css) {
            throw StyleNotFoundException::fromStyleId($id);
        }

        if ( ! $style->isApproved()) {
            throw StyleNotApprovedException::fromStyleId($id);
        }

        return $style;
    }

    public function approve(Css $style, User $user): bool
    {
        $style->approve();

        if ( ! $this->css->approveStyle(
            CssHistory::newTransaction(
                Status::get(Status::APPROVED), $user, $style
            )
        )) {
            throw StyleAlreadyApprovedException::fromStyleId($style->getId());
        }

        return true;
    }

    public function editPartial(int $id, array $data): int
    {
        throw new \Exception('Method getById() is not implemented.');
    }

    public function edit(int $id, array $data): int
    {
        throw new \Exception('Method edit() is not implemented.');
    }

    public function delete(int $id): int
    {
        throw new \Exception('Method delete() is not implemented.');
    }
}
