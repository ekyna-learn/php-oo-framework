<?php

namespace Test\Persistence\Mapping\Property;

use InvalidArgumentException;
use Persistence\Mapping\Property\PropertyInterface;
use Test\TestCase;

/**
 * Class AbstractPropertyTest
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
abstract class AbstractPropertyTest extends TestCase
{
    public function test_implements(): void
    {
        $reflection = $this->getReflectionClass();

        $this->assertTrue(
            $reflection->implementsInterface(PropertyInterface::class),
            "Class $this->class must implement " . PropertyInterface::class
        );
    }

    public function test_properties(): void
    {
        $this->assertProperty('name', [self::PRIVATE, self::PROTECTED]);
        $this->assertProperty('options', [self::PRIVATE, self::PROTECTED]);
    }

    public function test_construct(): void
    {
        $name = $this->getReflectionProperty('name');
        $options = $this->getReflectionProperty('options');

        $this->assertConstructor([
            ['name' => 'name', 'type' => 'string'],
            ['name' => 'options', 'type' => 'array'],
        ]);

        $property = $this->create('Test');

        $name->setAccessible(true);
        $this->assertEquals(
            'Test', $name->getValue($property),
            "$this->class::__construct should initialize 'name' property with 'name' argument value"
        );

        $options->setAccessible(true);
        $this->assertEquals(
            $this->getOptionsDefaults(), $options->getValue($property),
            "$this->class::__construct should initialize 'options' property with defaults if 'options' argument is empty"
        );
    }

    public function test_getName(): void
    {
        $this->skipIfMethodIsNotDefined('getName');

        $property = $this->create('Test');

        $this->assertEquals(
            'Test', $property->getName($property),
            "$this->class::getName() should return the 'name' property value"
        );
    }

    public function test_convertToPhPValue(): void
    {
        $this->skipIfMethodIsNotDefined('convertToPhPValue');

        foreach ($this->provideConvertToPhPValue() as $values) {
            $this->assertEquals(
                $values[1], $actual = $this->create('test')->convertToPhPValue($values[0]),
                $this->conversionErrorMessage('convertToPhPValue', $values, $actual)
            );
        }
    }

    abstract public function provideConvertToPhPValue(): array;

    public function test_convertToDatabaseValue(): void
    {
        $this->skipIfMethodIsNotDefined('convertToDatabaseValue');

        foreach ($this->provideConvertToDatabaseValue() as $values) {
            $this->assertEquals(
                $values[1], $actual = $this->create('test')->convertToDatabaseValue($values[0]),
                $this->conversionErrorMessage('convertToDatabaseValue', $values, $actual)
            );
        }

        $this->assertNull(
            $this->create('test', ['nullable' => true])->convertToDatabaseValue(null),
            "$this->class::convertToDatabaseValue should allow 'NULL' if nullable option is 'TRUE'"
        );

        $this->expectException(InvalidArgumentException::class);

        $this->create('test', ['nullable' => false])->convertToDatabaseValue(null);
    }

    abstract public function provideConvertToDatabaseValue(): array;

    protected function getOptionsDefaults(): array
    {
        return [
            'nullable' => false,
        ];
    }
}
