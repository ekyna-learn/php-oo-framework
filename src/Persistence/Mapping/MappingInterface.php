<?php

namespace Persistence\Mapping;

use Persistence\Mapping\Property\PropertyInterface;

/**
 * Interface MappingInterface
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
interface MappingInterface
{
    /**
     * Returns the class.
     *
     * @return string
     */
    public function getClass(): string;

    /**
     * Returns the table.
     *
     * @return string
     */
    public function getTable(): string;

    /**
     * Returns the properties.
     *
     * @return PropertyInterface[]
     */
    public function getProperties(): array;

    /**
     * Returns the repository class.
     *
     * @return string
     */
    public function getRepositoryClass(): string;

    /**
     * Returns the manager class.
     *
     * @return string
     */
    public function getManagerClass(): string;
}
