<?php

namespace Test\Form\Form;

use Form\Field\FieldInterface;
use InvalidArgumentException;
use Symfony\Component\DomCrawler\Crawler;
use Test\TestCase;

/**
 * Class AbstractFieldTest
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
abstract class AbstractFieldTest extends TestCase
{
    protected $widgetTag = 'input';

    public function test_implements(): void
    {
        $reflection = $this->getReflectionClass();

        $this->assertTrue(
            $reflection->implementsInterface(FieldInterface::class),
            "Class $this->class must implement " . FieldInterface::class
        );
    }

    public function test_properties(): void
    {
        $this->assertProperty('name', [self::PRIVATE, self::PROTECTED]);
        $this->assertProperty('label', [self::PRIVATE, self::PROTECTED]);
        $this->assertProperty('options', [self::PRIVATE, self::PROTECTED]);
    }

    public function test_construct(): void
    {
        $name = $this->getReflectionProperty('name');
        $label = $this->getReflectionProperty('label');
        $options = $this->getReflectionProperty('options');

        $this->assertConstructor([
            ['name' => 'name', 'type' => 'string'],
            ['name' => 'label', 'type' => 'string', 'nullable' => true],
            ['name' => 'options', 'type' => 'array'],
        ]);

        $field = $this->create('Test');

        $name->setAccessible(true);
        $this->assertEquals(
            'Test', $name->getValue($field),
            "$this->class::__construct should initialize 'name' property with 'name' argument value"
        );

        $label->setAccessible(true);
        $this->assertEquals(
            'Test', $label->getValue($field),
            "$this->class::__construct should initialize 'label' property with 'name' argument value if 'label' argument is null"
        );

        $options->setAccessible(true);
        $this->assertEquals(
            $this->getOptionsDefaults(), $options->getValue($field),
            "$this->class::__construct should initialize 'options' property with defaults if 'options' argument is empty"
        );
    }

    protected function getOptionsDefaults(): array
    {
        return [
            'required' => true,
            'disabled' => false,
        ];
    }

    public function test_convertToPhpValue(): void
    {
        $this->skipIfMethodIsNotDefined('convertToPhPValue');

        foreach ($this->provideConvertToPhpValue() as $values) {
            if (is_object($values[0]) || is_object($values[1])) {
                $this->assertEquals(
                    $values[1], $actual = $this->create('test')->convertToPhpValue($values[0]),
                    $this->conversionErrorMessage('convertToPhpValue', $values, $actual)
                );
            } else {
                $this->assertSame(
                    $values[1], $actual = $this->create('test')->convertToPhpValue($values[0]),
                    $this->conversionErrorMessage('convertToPhpValue', $values, $actual)
                );
            }
        }
    }

    abstract public function provideConvertToPhpValue(): array;

    public function test_convertToPhpValue_withNull(): void
    {
        $this->skipIfMethodIsNotDefined('convertToPhPValue');

        $this->assertNull(
            $this->create('test', 'test', ['required' => false])->convertToPhpValue(null),
            "$this->class::convertToPhpValue should allow 'NULL' if required option is 'FALSE'"
        );

        $this->expectException(InvalidArgumentException::class);

        $this->create('test', 'test', ['required' => true])->convertToPhpValue(null);
    }

    public function test_convertToHtmlValue(): void
    {
        $this->skipIfMethodIsNotDefined('convertToHtmlValue');

        foreach ($this->provideConvertToHtmlValue() as $values) {
            $this->assertSame(
                $values[1], $actual = $this->create('test')->convertToHtmlValue($values[0]),
                $this->conversionErrorMessage('convertToHtmlValue', $values, $actual)
            );
        }
    }

    abstract public function provideConvertToHtmlValue(): array;

    public function test_render(): void
    {
        $this->skipIfMethodIsNotDefined('render');

        foreach ($this->provideRender() as $config) {
            [$name, $label, $options, $value] = $config;
            $field = $this->create($name, $label, $options);

            $crawler = new Crawler($field->render($value));
            $group = $this->assertGroup($crawler);
            $this->assertLabel($group, $field);
            $this->assertWidget($group, $field, $value);
        }
    }

    abstract public function provideRender(): array;

    protected function assertGroup(Crawler $crawler): Crawler
    {
        $crawler = $crawler->filter('div');

        $this->assertEquals(
            1, $crawler->count(),
            "$this->class::render() method should render the div html tag"
        );

        $this->assertGroupAttributes($crawler);

        return $crawler;
    }

    protected function assertGroupAttributes(Crawler $crawler): void
    {
        $this->assertAttributes($crawler, ['class' => 'form-group']);
    }

    protected function assertLabel(Crawler $crawler, FieldInterface $field): Crawler
    {
        $crawler = $crawler->children('label');

        $this->assertEquals(
            1, $crawler->count(),
            "$this->class::render() method should render the label html tag as a child of the group (div html tag)"
        );

        $this->assertLabelAttributes($crawler, $field);

        $this->assertEquals($field->getLabel(), $crawler->text());

        return $crawler;
    }

    protected function assertLabelAttributes(Crawler $crawler, FieldInterface $field): void
    {
        $this->assertAttributes($crawler, ['for' => $field->getName()]);
    }

    protected function assertWidget(Crawler $crawler, FieldInterface $field, $value): Crawler
    {
        $crawler = $crawler->children($this->widgetTag);

        $this->assertEquals(
            1, $crawler->count(),
            "$this->class::render() method should render the widget as a child of the group (div html tag)"
        );

        $this->assertWidgetAttributes($crawler, $field, $value);

        return $crawler;
    }

    protected function assertWidgetAttributes(Crawler $crawler, FieldInterface $field, $value): void
    {
        $this->assertAttributes($crawler, [
            'type'  => 'text',
            'class' => 'form-control',
            'id'    => $field->getName(),
            'name'  => $field->getName(),
            //'placeholder' => $this->label,
            'value' => $value,
        ]);
    }

    protected function assertAttributes(Crawler $crawler, array $attributes): void
    {
        foreach ($attributes as $name => $value) {
            $this->assertEquals(
                $value, $crawler->attr($name),
                sprintf(
                    "%s::render() method should add attribute '%s' with proper value to element %s.",
                    $this->class, $name, $crawler->getNode(0)->nodeName
                )
            );
        }
    }
}
