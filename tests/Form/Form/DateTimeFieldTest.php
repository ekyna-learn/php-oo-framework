<?php

namespace Test\Form\Form;

use Form\Field\FieldInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class DateTimeFieldTest
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class DateTimeFieldTest extends AbstractFieldTest
{
    protected $class = 'Form\Field\DateTimeField';

    public function provideConvertToPhpValue(): array
    {
        return [
            ['2020-01-01', new \DateTime('2020-01-01')],
            ['1983-04-07', new \DateTime('1983-04-07')],
        ];
    }

    public function provideConvertToHtmlValue(): array
    {
        return [
            [null, null],
            [new \DateTime('1983-04-07'), '1983-04-07'],
        ];
    }

    public function provideRender(): array
    {
        return [
            ['name1', 'label1', [], '2020-01-01'],
            ['name2', 'label2', [], null],
        ];
    }

    protected function assertWidgetAttributes(Crawler $crawler, FieldInterface $field, $value): void
    {
        $this->assertAttributes($crawler, [
            'type'        => 'date',
            'class'       => 'form-control',
            'id'          => $field->getName(),
            'name'        => $field->getName(),
            //'placeholder' => $field->getLabel(),
            'value'       => $value,
        ]);
    }
}
