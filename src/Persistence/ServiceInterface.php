<?php

namespace Persistence;

use PDO;
use Persistence\Mapping\MappingInterface;

/**
 * Interface ServiceInterface
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
interface ServiceInterface
{
    /**
     * Sets the connection.
     *
     * @param PDO $connection
     */
    public function setConnection(PDO $connection): void;

    /**
     * Sets the mapping.
     *
     * @param MappingInterface $mapping
     */
    public function setMapping(MappingInterface $mapping): void;
}
