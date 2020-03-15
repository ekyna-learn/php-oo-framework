<?php

namespace Test\Form\Form;

/**
 * Class TextFieldTest
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class TextFieldTest extends AbstractFieldTest
{
    protected $class = 'Form\Field\TextField';

    public function provideConvertToPhpValue(): array
    {
        return [
            ['test', 'test'],
            ['   test   ', 'test'],
        ];
    }

    public function provideConvertToHtmlValue(): array
    {
        return [
            [null, null],
            ['test', 'test'],
            ['   test   ', 'test'],
        ];
    }

    public function provideRender(): array
    {
        return [
            ['name1', 'label1', [], 'value1'],
            ['name2', 'label2', [], null],
        ];
    }
}
