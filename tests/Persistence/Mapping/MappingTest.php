<?php

namespace Test\Persistence\Mapping;

use InvalidArgumentException;
use Persistence\Manager\ManagerInterface;
use Persistence\Mapping\Property\PropertyInterface;
use Persistence\Repository\RepositoryInterface;
use Test\Acme\Entity\Foo;
use Test\TestCase;
use Throwable;

/**
 * Class MappingTest
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class MappingTest extends TestCase
{
    protected $class = 'Persistence\Mapping\Mapping';

    public function test_properties(): void
    {
        $this->assertProperty('class', self::PRIVATE);
        $this->assertProperty('table', self::PRIVATE);
        $this->assertProperty('properties', self::PRIVATE);
    }

    public function test_constructor(): void
    {
        $this->assertConstructor([
            ['name' => 'class', 'type' => 'string'],
            ['name' => 'table', 'type' => 'string'],
        ]);

        $mapping = $this->createMapping(Foo::class, 'foo');

        $this->assertConstructorInitialize($mapping, 'class', Foo::class);
        $this->assertConstructorInitialize($mapping, 'table', 'foo');

        $rp = $this->getReflectionProperty('properties');
        $rp->setAccessible(true);
        $this->assertSame(
            [], $rp->getValue($mapping),
            "$this->class::__construct should initialize 'properties' property with an empty array"
        );
    }

    public function test_methods(): void
    {
        $this->assertMethod('getClass', self::PUBLIC, [], 'string');
        $this->assertMethod('getTable', self::PUBLIC, [], 'string');
        $this->assertMethod('getProperties', self::PUBLIC, [], 'array');

        $this->assertMethod('addProperty', self::PUBLIC, [
            ['name' => 'property', 'type' => PropertyInterface::class],
        ], $this->class);
    }

    public function test_getClass(): void
    {
        $this->skipIfMethodIsNotDefined('getClass');

        $mapping = $this->createMapping(Foo::class, 'foo');

        $this->assertSame(
            Foo::class, $mapping->getClass(),
            "$this->class::getClass() method should return the 'class' property value."
        );
    }

    public function test_getTable(): void
    {
        $this->skipIfMethodIsNotDefined('getTable');

        $mapping = $this->createMapping(Foo::class, 'foo');

        $this->assertSame(
            'foo', $mapping->getTable(),
            "$this->class::getTable() method should return the 'table' property value."
        );
    }

    public function test_getProperties(): void
    {
        $this->skipIfMethodIsNotDefined('getProperties');

        $mapping = $this->createMapping(Foo::class, 'foo');

        $this->assertSame(
            [], $mapping->getProperties(),
            "$this->class::getProperties() method should return the 'properties' property value."
        );

        $rp = $this->getReflectionProperty('properties');
        $rp->setAccessible(true);
        $rp->setValue($mapping, $expected = ['foo' => $this->mockProperty('bar')]);

        $this->assertSame(
            $expected, $mapping->getProperties(),
            "$this->class::getProperties() method should return the 'properties' property value."
        );
    }

    public function test_addProperty(): void
    {
        $this->skipIfMethodIsNotDefined('addProperty');

        $form = $this->createMapping(Foo::class, 'foo');
        $form->addProperty($this->mockProperty('bar'));

        $rp = $this->getReflectionProperty('properties');
        $rp->setAccessible(true);

        $properties = $rp->getValue($form);

        $this->assertCount(1, $properties);
        $property = reset($properties);

        $this->assertInstanceOf(PropertyInterface::class, $property);
        /** @var PropertyInterface $property */
        $this->assertSame('bar', $property->getName());

        $message =
            "$this->class::addProperty() method should throw an InvalidArgumentException " .
            "if a property with same name has already been added.";

        try {
            $form->addProperty($this->mockProperty('bar'));
        } catch (Throwable $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e, $message);

            return;
        }

        $this->fail($message);
    }

    public function test_getRepositoryClass(): void
    {
        $this->skipIfMethodIsNotDefined('getRepositoryClass');

        $mapping = $this->createMapping(Foo::class, 'foo');

        $class = $mapping->getRepositoryClass();

        $this->assertTrue(
            $class === self::REPOSITORY_CLASS || is_subclass_of($class, RepositoryInterface::class),
            sprintf(
                "%s::%s() should return class %s or as class that implement %s",
                $this->class,
                'getRepositoryClass',
                self::REPOSITORY_CLASS,
                RepositoryInterface::class
            )
        );
    }

    public function test_getManagerClass(): void
    {
        $this->skipIfMethodIsNotDefined('getManagerClass');

        $mapping = $this->createMapping(Foo::class, 'foo');

        $class = $mapping->getManagerClass();

        $this->assertTrue(
            $class === self::MANAGER_CLASS || is_subclass_of($class, ManagerInterface::class),
            sprintf(
                "%s::%s() should return class %s or as class that implement %s",
                $this->class,
                'getManagerClass',
                self::MANAGER_CLASS,
                ManagerInterface::class
            )
        );
    }

    /** @return PropertyInterface|\PHPUnit\Framework\MockObject\MockObject */
    private function mockProperty(string $name): PropertyInterface
    {
        $mock = $this->createMock(PropertyInterface::class);
        $mock->expects($this->any())->method('getName')->willReturn($name);

        return $mock;
    }

    /** @return \Persistence\Mapping\Mapping */
    private function createMapping(string $class, string $table)
    {
        return new $this->class($class, $table);
    }
}
