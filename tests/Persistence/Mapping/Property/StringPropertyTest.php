<?php

namespace Test\Persistence\Mapping\Property;

/**
 * Class StringPropertyTest
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class StringPropertyTest extends AbstractPropertyTest
{
    protected $class = 'Persistence\Mapping\Property\StringProperty';

    public function provideConvertToPhPValue(): array
    {
        return [
            [null, null],
            ['foo', 'foo'],
            ['bar', 'bar'],
        ];
    }

    public function provideConvertToDatabaseValue(): array
    {
        return [
            ['foo', 'foo'],
            ['bar', 'bar'],
        ];
    }
}
