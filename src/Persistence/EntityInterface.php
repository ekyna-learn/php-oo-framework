<?php

namespace Persistence;

/**
 * Interface EntityInterface
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
interface EntityInterface
{
    /**
     * Returns the id.
     *
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * Sets the id.
     *
     * @param int|null $id
     */
    public function setId(int $id = null): void;
}
