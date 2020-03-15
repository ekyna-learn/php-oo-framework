<?php

namespace Persistence\Repository;

use Persistence\EntityInterface;
use Persistence\ServiceInterface;

/**
 * Interface RepositoryInterface
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface RepositoryInterface extends ServiceInterface
{
    /**
     * Returns all the entities.
     *
     * @return array
     */
    public function findAll(): array;

    /**
     * Returns the entity by its id.
     *
     * @param int $id
     *
     * @return EntityInterface|null
     */
    public function findOneById(int $id): ?EntityInterface;
}
