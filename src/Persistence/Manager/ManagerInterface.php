<?php

namespace Persistence\Manager;

use Persistence\EntityInterface;
use Persistence\ServiceInterface;

/**
 * Interface ManagerInterface
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface ManagerInterface extends ServiceInterface
{
    /**
     * Persists the given entity into the database.
     *
     * @param EntityInterface $entity
     *
     * @return bool Whether it succeed
     */
    public function persist(EntityInterface $entity): bool;

    /**
     * Removes the entity from the database.
     *
     * @param EntityInterface $entity
     *
     * @return bool Whether it succeed
     */
    public function remove(EntityInterface $entity): bool;
}
