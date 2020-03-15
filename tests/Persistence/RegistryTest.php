<?php

namespace Test\Persistence;

use InvalidArgumentException;
use PDO;
use Persistence\Manager\ManagerInterface;
use Persistence\Mapping\MappingInterface;
use Persistence\Repository\RepositoryInterface;
use ReflectionClass;
use Test\Acme\Entity\Bar;
use Test\Acme\Entity\Foo;
use Throwable;

/**
 * Class RegistryTest
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class RegistryTest extends AbstractEntityServiceTest
{
    protected $class = 'Persistence\Registry';

    public function test_properties(): void
    {
        $this->assertProperty('connection', self::PRIVATE);
        $this->assertProperty('mappings', self::PRIVATE);
    }

    public function test_constructor(): void
    {
        $this->skipIfPropertyIsNotDefined('connection');
        $this->skipIfPropertyIsNotDefined('mappings');

        $this->assertConstructor([
            ['name' => 'connection', 'type' => PDO::class],
        ]);

        $registry = $this->create();

        $this->assertConstructorInitialize($registry, 'connection', $this->getConnectionMock());

        $rp = $this->getReflectionProperty('mappings');
        $rp->setAccessible(true);
        $this->assertSame(
            [], $rp->getValue($registry),
            "$this->class::__construct should initialize 'mappings' property with an empty array"
        );
    }

    public function test_methods(): void
    {
        $this->assertMethod('registerMapping', self::PUBLIC, [
            ['name' => 'mapping', 'type' => MappingInterface::class],
        ]);

        $this->assertMethod('getMapping', self::PUBLIC, [
            ['name' => 'entityClass', 'type' => 'string'],
        ], MappingInterface::class);

        $this->assertMethod('getRepository', self::PUBLIC, [
            ['name' => 'entityClass', 'type' => 'string'],
        ], RepositoryInterface::class);

        $this->assertMethod('getManager', self::PUBLIC, [
            ['name' => 'entityClass', 'type' => 'string'],
        ], ManagerInterface::class);
    }

    public function test_registerMapping(): void
    {
        $this->skipIfMethodIsNotDefined('registerMapping');

        $rp = $this->getReflectionProperty('mappings');
        $rp->setAccessible(true);

        $registry = $this->create();

        $fooMapping = $this->getMappingMock();
        $registry->registerMapping($fooMapping);
        $this->assertContains(
            $fooMapping, $rp->getValue($registry),
            "$this->class::registerMapping() should add the given mapping to the 'mappings' property"
        );

        $barMapping = $this->mockMapping(Bar::class, 'bar');
        $registry->registerMapping($barMapping);
        $this->assertContains(
            $barMapping, $rp->getValue($registry),
            "$this->class::registerMapping() should add the given mapping to the 'mappings' property"
        );

        $message =
            "$this->class::registerMapping() should throw InvalidArgumentException " .
            "if a mapping is already registered for the same class";

        try {
            $registry->registerMapping($fooMapping);
        } catch (Throwable $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e, $message);

            return;
        }

        $this->fail($message);
    }

    public function test_getMapping(): void
    {
        $this->skipIfMethodIsNotDefined('registerMapping');
        $rp = $this->getReflectionProperty('mappings');
        $rp->setAccessible(true);

        $fooMapping = $this->getMappingMock();
        $barMapping = $this->mockMapping(Bar::class, 'bar');

        $registry = $this->create();
        $rp->setValue($registry, [
            Foo::class => $fooMapping,
            Bar::class => $barMapping,
        ]);

        $message = "$this->class::getMapping() method should return the mapping matching the 'class' argument value";

        $this->assertSame($barMapping, $registry->getMapping(Bar::class), $message);
        $this->assertSame($fooMapping, $registry->getMapping(Foo::class), $message);

        $message =
            "$this->class::getMapping() method should throw an InvalidArgumentException " .
            "if no mapping is registered for the 'class' argument value.";

        try {
            $registry->getManager('UnknownClass');
        } catch (Throwable $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e, $message);

            return;
        }

        $this->fail($message);
    }

    public function test_getRepository(): void
    {
        $this->skipIfClassDoesNotExist(self::REPOSITORY_CLASS);

        $this->skipIfMethodIsNotDefined('registerMapping');
        $rp = $this->getReflectionProperty('mappings');
        $rp->setAccessible(true);

        $fooMapping = $this->getMappingMock();
        $barMapping = $this->mockMapping(Bar::class, 'bar');

        $registry = $this->create();
        $rp->setValue($registry, [
            Foo::class => $fooMapping,
            Bar::class => $barMapping,
        ]);

        $this->assertRepository($registry->getRepository(Foo::class), $fooMapping);
        $this->assertRepository($registry->getRepository(Bar::class), $barMapping);
    }

    private function assertRepository(RepositoryInterface $repository, MappingInterface $mapping): void
    {
        $rc = new ReflectionClass(self::REPOSITORY_CLASS);
        $rp = $rc->getProperty('mapping');
        $rp->setAccessible(true);

        $this->assertSame(
            $mapping, $rp->getValue($repository),
            "$this->class::getRepository() should set the repository 'mapping' " .
            "property with the mapping matching the given entity class."
        );

        $rp = $rc->getProperty('connection');
        $rp->setAccessible(true);

        $this->assertSame(
            $this->getConnectionMock(), $rp->getValue($repository),
            "$this->class::getRepository() should set the repository 'connection' " .
            "property with the proper value."
        );
    }

    public function test_getManager(): void
    {
        $this->skipIfClassDoesNotExist(self::MANAGER_CLASS);

        $this->skipIfMethodIsNotDefined('registerMapping');
        $rp = $this->getReflectionProperty('mappings');
        $rp->setAccessible(true);

        $fooMapping = $this->getMappingMock();
        $barMapping = $this->mockMapping(Bar::class, 'bar');

        $registry = $this->create();
        $rp->setValue($registry, [
            Foo::class => $fooMapping,
            Bar::class => $barMapping,
        ]);

        $this->assertManager($registry->getManager(Foo::class), $fooMapping);
        $this->assertManager($registry->getManager(Bar::class), $barMapping);
    }

    private function assertManager(ManagerInterface $manager, MappingInterface $mapping): void
    {
        $rc = new ReflectionClass(self::MANAGER_CLASS);
        $rp = $rc->getProperty('mapping');
        $rp->setAccessible(true);

        $this->assertSame(
            $mapping, $rp->getValue($manager),
            "$this->class::getManager() should set the manager 'mapping' " .
            "property with the mapping matching the given entity class."
        );

        $rp = $rc->getProperty('connection');
        $rp->setAccessible(true);

        $this->assertSame(
            $this->getConnectionMock(), $rp->getValue($manager),
            "$this->class::getManager() should set the manager 'connection' " .
            "property with the proper value."
        );
    }

    /** @return \Persistence\Registry */
    protected function create(...$args)
    {
        return new $this->class($this->getConnectionMock());
    }
}
