<?php

namespace Test\Form\Form;

use Form\Field\FieldInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class CheckboxFieldTest
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class CheckboxFieldTest extends AbstractFieldTest
{
    protected $class = 'Form\Field\CheckboxField';

    protected function getOptionsDefaults(): array
    {
        return [
            'required' => false,
            'disabled' => false,
        ];
    }

    public function provideConvertToPhpValue(): array
    {
        return [
            [0, false],
            [1, true],
        ];
    }

    public function provideConvertToHtmlValue(): array
    {
        return [
            [null, null],
            [false, 0],
            [true, 1],
        ];
    }

    public function provideRender(): array
    {
        return [
            ['name1', 'label1', [], true],
            ['name2', 'label2', [], false],
            ['name2', 'label2', [], null],
        ];
    }

    protected function assertGroupAttributes(Crawler $crawler): void
    {
        $this->assertAttributes($crawler, ['class' => 'form-group form-check']);
    }

    protected function assertWidgetAttributes(Crawler $crawler, FieldInterface $field, $value): void
    {
        $this->assertAttributes($crawler, [
            'type'    => 'checkbox',
            'class'   => 'form-check-input',
            'id'      => $field->getName(),
            'name'    => $field->getName(),
            'value'   => 1,
            'checked' => $value ? 'checked' : null,
        ]);
    }
}
