<?php

namespace Test\Service;

use LogicException;
use Test\Acme\Entity\Foo;
use Test\TestCase;

/**
 * Class ContainerTest
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ContainerTest extends TestCase
{
    protected $class = 'Service\Container';

    public function test_properties(): void
    {
        $this->assertProperty('instance', self::PRIVATE, true);
        $this->assertProperty('definitions', self::PRIVATE);
        $this->assertProperty('services', self::PRIVATE);
    }

    public function test_constructor(): void
    {
        $this->assertMethod('__construct', self::PRIVATE);
    }

    public function test_getInstance(): void
    {
        $this->assertMethod('getInstance', self::PUBLIC, [], $this->class, false, true);

        $container = $this->createContainer();

        $this->assertSame(
            $container,
            $this->createContainer(),
            "Method $this->class::getInstance should always return the same instance."
        );

        $property = $this->getReflectionProperty('definitions');
        $property->setAccessible(true);
        $this->assertSame(
            [], $property->getValue($container),
            "$this->class::__construct should initialize property 'definitions' with an empty array."
        );

        $property = $this->getReflectionProperty('services');
        $property->setAccessible(true);
        $this->assertSame(
            [], $property->getValue($container),
            "$this->class::__construct should initialize property 'services' with an empty array."
        );
    }

    public function test_has(): void
    {
        $this->assertMethod('has', self::PUBLIC, [
            ['name' => 'id', 'type' => 'string'],
        ], 'bool');

        $container = $this->createContainer();

        $property = $this->getReflectionProperty('definitions');
        $property->setAccessible(true);
        $property->setValue($container, ['test1' => 'foo']);

        $this->assertTrue(
            $container->has('test1'),
            sprintf("Method $this->class::has() should return true if a definition is set for the given 'id'.")
        );

        $this->assertFalse(
            $container->has('bar'),
            sprintf("Method $this->class::has() should return false if no definition is set for the given 'id'.")
        );
    }

    public function test_register(): void
    {
        $this->assertMethod('register', self::PUBLIC, [
            ['name' => 'id', 'type' => 'string'],
            ['name' => 'definition'],
        ]);

        $container = $this->createContainer();

        $container->register('test2', 'bar');

        $property = $this->getReflectionProperty('definitions');
        $property->setAccessible(true);

        $definitions = $property->getValue($container);

        $this->assertTrue(
            isset($definitions['test2']) && $definitions['test2'] === 'bar',
            "Method $this->class::registrer() should add the 'definition' argument value to the 'definition property with 'id' as its key."
        );

        try {
            $container->register('test2', 'bar');

        } catch (\Exception $e) {
            $this->assertInstanceOf(LogicException::class, $e);

            return;
        }

        $this->fail("Method $this->class::registrer() should throw a LogicException if a service is already register.");
    }

    public function test_get_class(): void
    {
        $this->assertMethod('get', self::PUBLIC, [
            ['name' => 'id', 'type' => 'string'],
        ], 'object');

        $container = $this->createContainer();

        $container->register('test3', Foo::class);

        $service = $container->get('test3');

        $this->assertInstanceOf(
            Foo::class, $service,
            "If a service definition is a class, $this->class::get() method should return an instance of this class."
        );

        $this->assertSameService($container, 'test3', $service);
    }

    public function test_get_callable(): void
    {
        $this->assertMethod('get', self::PUBLIC, [
            ['name' => 'id', 'type' => 'string'],
        ], 'object');

        $container = $this->createContainer();

        $container->register('test4', function() {
            return new Foo();
        });

        $service = $container->get('test4');

        $this->assertInstanceOf(
            Foo::class, $service,
            "If a service definition is a callable, $this->class::get() method should invoke this callable and return its result."
        );

        $this->assertSameService($container, 'test4', $service);
    }

    public function test_get_object(): void
    {
        $this->assertMethod('get', self::PUBLIC, [
            ['name' => 'id', 'type' => 'string'],
        ], 'object');

        $container = $this->createContainer();

        $service = new Foo();

        $container->register('test5', $service);

        $service = $container->get('test5');

        $this->assertInstanceOf(
            Foo::class, $service,
            "If a service definition is an object, $this->class::get() method should return this object."
        );

        $this->assertSameService($container, 'test5', $service);
    }

    /** @param \Service\Container $container */
    private function assertSameService($container, string $id, object $service)
    {
        $services = $this->getReflectionProperty('services');
        $services->setAccessible(true);

        $services->getValue($container);

        $this->assertContains(
            $service, $services->getValue($container),
            "$this->class::get() method should store the returned object in the 'services' property."
        );

        $this->assertSame(
            $service, $container->get($id),
            "$this->class::get() method should always return the same object for the same 'id' argument value"
        );
    }

    /** @return \Service\Container */
    private function createContainer()
    {
        return call_user_func("$this->class::getInstance");
    }
}
