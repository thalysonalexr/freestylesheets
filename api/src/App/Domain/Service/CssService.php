<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Css;
use App\Domain\Entity\User;
use App\Domain\Value\Author;
use App\Domain\Value\Tag;
use App\Domain\Value\CssHistory;
use App\Domain\Value\Status;
use App\Domain\Service\Exception\StyleExistsException;
use App\Domain\Service\Exception\StyleNotFoundException;
use App\Domain\Service\Exception\StyleNotApprovedException;
use App\Domain\Service\Exception\StyleAlreadyApprovedException;
use App\Domain\Service\Exception\InvalidStatusException;
use App\Infrastructure\Repository\Css as CssRepository;

final class CssService implements CssServiceInterface
{
    /**
     * @var Css
     */
    private $css;

    /**
     * Constructor this class
     *
     * @param Css $css                      style object repository
     */
    public function __construct(CssRepository $css)
    {
        $this->css = $css;
    }

    /**
     * { @inheritdoc }
     * @throws StyleExistsException         if unique id exists in database
     */
    public function register(string $name, string $description, string $style, Author $author, ?Tag $tag): int
    {
        try {
            return $this->css->add(Css::new(null, $name, $description, $style, $author, $tag));
        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            throw StyleExistsException::fromName($name);
        }
    }

    /**
     * { @inheritdoc }
     */
    public function getAll(array $filters = []): array
    {
        return $this->css->all($filters);
    }

    /**
     * { @inheritdoc }
     * @throws StyleNotFoundException           if style not found
     */
    public function getById(int $id): ?Css
    {
        $style = $this->css->findById($id);

        if ( ! $style instanceof Css) {
            throw StyleNotFoundException::fromStyleId($id);
        }

        return $style;
    }

    /**
     * { @inheritdoc }
     * @throws StyleNotFoundException           if style not found
     * @throws StyleNotApprovedException        if style not approved
     */
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

    /**
     * { @inheritdoc }
     * @throws InvalidStatusException           if status is invalid
     * @throws StyleAlreadyApprovedException    if style already approved
     */
    public function approve(Css $style, User $user): bool
    {
        if ($style->isApproved() === Status::APPROVED) {
            throw InvalidStatusException::approve();
        }

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
