<?php

declare(strict_types=1);

namespace Kommandhub\Foundation\EntityHandler;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;

/**
 * Base abstraction for Shopware DAL entity delete operations.
 *
 * @template TCollection of \Shopware\Core\Framework\DataAbstractionLayer\EntityCollection
 */
abstract class AbstractEntityDeleter
{
    /**
     * @param EntityRepository<TCollection> $repository The repository for the entity to be deleted
     */
    public function __construct(
        protected EntityRepository $repository
    ) {
    }

    /**
     * Deletes a single entity by identifier.
     *
     * @param string $id Entity identifier
     * @param Context $context Shopware execution context
     *
     * @return void
     */
    public function delete(
        string $id,
        Context $context
    ): void {
        $this->repository->delete(
            [['id' => $id]],
            $context
        );
    }

    /**
     * Deletes multiple entities by identifiers.
     *
     * @param array<string> $ids Entity identifiers
     * @param Context $context Shopware execution context
     *
     * @return void
     */
    public function deleteMany(
        array $ids,
        Context $context
    ): void {
        if ($ids === []) {
            return;
        }

        $this->repository->delete(
            array_map(
                static fn (string $id): array => ['id' => $id],
                $ids
            ),
            $context
        );
    }
}
