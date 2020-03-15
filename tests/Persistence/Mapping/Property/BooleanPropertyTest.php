<?php

namespace Test\Persistence\Mapping\Property;

/**
 * Class BooleanPropertyTest
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class BooleanPropertyTest extends AbstractPropertyTest
{
    protected $class = 'Persistence\Mapping\Property\BooleanProperty';

    public function provideConvertToPhPValue(): array
    {
        return [
            [null, false],
            [0, false],
            [1, true],
        ];
    }

    public function provideConvertToDatabaseValue(): array
    {
        return [
            [false, 0],
            [true, 1],
        ];
    }
}
