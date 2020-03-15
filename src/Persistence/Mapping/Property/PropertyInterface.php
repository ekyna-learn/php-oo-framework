<?php

namespace Persistence\Mapping\Property;

/**
 * Interface PropertyInterface
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface PropertyInterface
{
    /**
     * Returns the type name
     *
     * @return string
     */
    public function getName();

    /**
     * Converts a database data to a PHP data.
     *
     * @param mixed $data The database data
     *
     * @return mixed The PHP data
     */
    public function convertToPhpValue($data);

    /**
     * Converts a PHP data to a database data.
     *
     * @param mixed $data The PHP data
     *
     * @return mixed The database data
     */
    public function convertToDatabaseValue($data);
}
