<?php

namespace Test\Persistence\Manager;

use PHPUnit\Framework\Constraint\Constraint;

/**
 * Class ParameterConstraint
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ParameterConstraint extends Constraint
{
    private const PARAMETER = '~^[a-zA-Z][a-zA-Z0-9_]*[a-zA-Z0-9]$~';

    private $values;

    public function __construct($values)
    {
        $this->values = array_values($values);
    }

    public function toString(): string
    {
        return \sprintf(
            'keys matches %s and values are %s',
            self::PARAMETER,
            implode(', ', $this->values)
        );
    }

    protected function matches($parameters): bool
    {
        if (!\is_array($parameters)) {
            return false;
        }

        foreach ($parameters as $key => $value) {
            if (!\preg_match(self::PARAMETER, $key)) {
                return false;
            }
            if (!in_array($value, $this->values, true)) {
                return false;
            }
        }

        return true;
    }
}
