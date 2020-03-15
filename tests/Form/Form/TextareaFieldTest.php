<?php

namespace Test\Form\Form;

use Form\Field\FieldInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class TextareaFieldTest
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class TextareaFieldTest extends TextFieldTest
{
    protected $class = 'Form\Field\TextareaField';
    protected $widgetTag = 'textarea';

    protected function assertWidget(Crawler $crawler, FieldInterface $field, $value): Crawler
    {
        $crawler = parent::assertWidget($crawler, $field, $value);

        $this->assertEquals($value, $crawler->text());

        return $crawler;
    }

    protected function assertWidgetAttributes(Crawler $crawler, FieldInterface $field, $value): void
    {
        $this->assertAttributes($crawler, [
            'class'       => 'form-control',
            'id'          => $field->getName(),
            'name'        => $field->getName(),
        ]);
    }
}
