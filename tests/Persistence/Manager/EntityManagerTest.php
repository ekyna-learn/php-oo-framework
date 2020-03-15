<?php

namespace Test\Persistence\Manager;

use Persistence\EntityInterface;
use Persistence\Manager\ManagerInterface;
use PHPUnit\Framework\Constraint\RegularExpression;
use Test\Acme\Entity\Foo;
use Test\Persistence\AbstractEntityServiceTest;

/**
 * Class EntityManagerTest
 * @author Étienne Dauvergne <contact@ekyna.com>
 *
 * @method ManagerInterface create(...$args)
 */
class EntityManagerTest extends AbstractEntityServiceTest
{

    private const INSERT =
        '~^INSERT\s+INTO\s+' . self::TABLE .
        '\s*\(' . self::FIELDS . '\)' .
        '\s+VALUES\s+\(' . self::PARAMETERS . '\)' .
        self::END . '$~';

    private const UPDATE =
        '~^UPDATE\s+' . self::TABLE .
        '\s+SET\+s' . self::ASSIGNMENTS .
        '\s+WHERE\s+' . self::ASSIGNMENT .
        self::LIMIT . self::END . '$~';

    private const DELETE =
        '~^DELETE\s+FROM\s+' . self::TABLE .
        '\s+WHERE\s+' . self::ASSIGNMENT .
        self::LIMIT . self::END . '$~';

    protected $class = 'Persistence\Manager\EntityManager';

    public function test_methods(): void
    {
        parent::test_methods();

        $this->assertMethod('persist', self::PUBLIC, [
            ['name' => 'entity', 'type' => EntityInterface::class],
        ], 'bool');

        $this->assertMethod('remove', self::PUBLIC, [
            ['name' => 'entity', 'type' => EntityInterface::class],
        ], 'bool');
    }

    public function test_persist_withoutId_success(): void
    {
        $this->skipIfMethodIsNotDefined('setConnection');
        $this->skipIfMethodIsNotDefined('setMapping');
        $this->skipIfMethodIsNotDefined('persist');

        $statement = $this->mockStatement();
        $statement
            ->expects($this->once())
            ->method('execute')
            ->with(new ParameterConstraint(['iValue1', 'iValue2']))
            ->willReturn(true);

        $connection = $this->getConnectionMock();
        $connection
            ->expects($this->once())
            ->method('prepare')
            ->with(new RegularExpression(self::INSERT))
            ->willReturn($statement);

        $connection
            ->expects($this->once())
            ->method('lastInsertId')
            ->willReturn(13);

        $foo = new Foo();
        $foo->setField1('oValue1');
        $foo->setField2('oValue2');

        $manager = $this->create();

        $this->assertSame(
            true, $manager->persist($foo),
            "$this->class::persist() should return 'TRUE' if query succeeded"
        );
        $this->assertSame(
            13, $foo->getId(),
            "$this->class::persist() method should set entity's 'id' property after insertion"
        );
        $this->assertSame(
            'oValue1', $foo->getField1(),
            "$this->class::persist() method should not change set entity properties values after insertion"
        );
        $this->assertSame(
            'oValue2', $foo->getField2(),
            "$this->class::persist() method should not change set entity properties values after insertion"
        );
    }

    public function test_persist_withoutId_failure(): void
    {
        $this->skipIfMethodIsNotDefined('setConnection');
        $this->skipIfMethodIsNotDefined('setMapping');
        $this->skipIfMethodIsNotDefined('persist');

        $statement = $this->mockStatement();
        $statement
            ->expects($this->once())
            ->method('execute')
            ->with(new ParameterConstraint(['iValue1', 'iValue2']))
            ->willReturn(false);

        $connection = $this->getConnectionMock();
        $connection
            ->expects($this->once())
            ->method('prepare')
            ->with(new RegularExpression(self::INSERT))
            ->willReturn($statement);

        $foo = new Foo();
        $foo->setField1('oValue1');
        $foo->setField2('oValue2');

        $manager = $this->create();

        $this->assertSame(
            false, $manager->persist($foo),
            "$this->class::persist() should return 'FAILURE' if query failed"
        );
    }

    public function test_persist_withtId_success(): void
    {
        $this->skipIfMethodIsNotDefined('setConnection');
        $this->skipIfMethodIsNotDefined('setMapping');
        $this->skipIfMethodIsNotDefined('persist');

        $statement = $this->mockStatement();
        $statement
            ->expects($this->once())
            ->method('execute')
            ->with(new ParameterConstraint(['iValue1', 'iValue2', 14]))
            ->willReturn(true);

        $connection = $this->getConnectionMock();
        $connection
            ->expects($this->once())
            ->method('prepare')
            ->with(new RegularExpression(self::UPDATE))
            ->willReturn($statement);

        $connection
            ->expects($this->never())
            ->method('lastInsertId');

        $foo = new Foo();
        $foo->setId(14);
        $foo->setField1('oValue1');
        $foo->setField2('oValue2');

        $manager = $this->create();

        $this->assertSame(
            true, $manager->persist($foo),
            "$this->class::persist() should return 'TRUE' if query succeeded"
        );
        $this->assertSame(
            14, $foo->getId(),
            "$this->class::persist() method should not change set entity properties values after update"
        );
        $this->assertSame(
            'oValue1', $foo->getField1(),
            "$this->class::persist() method should not change set entity properties values after update"
        );
        $this->assertSame(
            'oValue2', $foo->getField2(),
            "$this->class::persist() method should not change set entity properties values after update"
        );
    }

    public function test_persist_withId_failure(): void
    {
        $this->skipIfMethodIsNotDefined('setConnection');
        $this->skipIfMethodIsNotDefined('setMapping');
        $this->skipIfMethodIsNotDefined('persist');

        $statement = $this->mockStatement();
        $statement
            ->expects($this->once())
            ->method('execute')
            ->with(new ParameterConstraint(['iValue1', 'iValue2', 15]))
            ->willReturn(false);

        $connection = $this->getConnectionMock();
        $connection
            ->expects($this->once())
            ->method('prepare')
            ->with(new RegularExpression(self::UPDATE))
            ->willReturn($statement);

        $foo = new Foo();
        $foo->setId(15);
        $foo->setField1('oValue1');
        $foo->setField2('oValue2');

        $manager = $this->create();

        $this->assertSame(
            false, $manager->persist($foo),
            "$this->class::persist() should return 'FAILURE' if query failed"
        );
    }

    public function test_remove_withoutId(): void
    {
        $this->skipIfMethodIsNotDefined('setConnection');
        $this->skipIfMethodIsNotDefined('setMapping');
        $this->skipIfMethodIsNotDefined('remove');

        $connection = $this->getConnectionMock();
        $connection
            ->expects($this->never())
            ->method('prepare');

        $manager = $this->create();
        $foo = new Foo();

        $this->assertSame(
            true, $manager->remove($foo),
            "$this->class::persist() should return 'TRUE' the entity id is 'NULL' (without trying to remove it from the database)"
        );
    }

    public function test_remove_withtId_success(): void
    {
        $this->skipIfMethodIsNotDefined('setConnection');
        $this->skipIfMethodIsNotDefined('setMapping');
        $this->skipIfMethodIsNotDefined('persist');

        $statement = $this->mockStatement();
        $statement
            ->expects($this->once())
            ->method('execute')
            ->with(new ParameterConstraint([16]))
            ->willReturn(true);

        $connection = $this->getConnectionMock();
        $connection
            ->expects($this->once())
            ->method('prepare')
            ->with(new RegularExpression(self::DELETE))
            ->willReturn($statement);

        $foo = new Foo();
        $foo->setId(16);
        $foo->setField1('oValue1');
        $foo->setField2('oValue2');

        $manager = $this->create();

        $this->assertSame(
            true, $manager->remove($foo),
            "$this->class::persist() should return 'TRUE' if query succeeded"
        );
        $this->assertNull(
            $foo->getId(),
            "$this->class::remove() method should set entity 'id' property to 'NULL' after deletion"
        );
        $this->assertSame(
            'oValue1', $foo->getField1(),
            "$this->class::persist() method should not change set entity properties (other than id) values after delete"
        );
        $this->assertSame(
            'oValue2', $foo->getField2(),
            "$this->class::persist() method should not change set entity properties (other than id) values after delete"
        );
    }

    public function test_remove_withId_failure(): void
    {
        $this->skipIfMethodIsNotDefined('setConnection');
        $this->skipIfMethodIsNotDefined('setMapping');
        $this->skipIfMethodIsNotDefined('persist');

        $statement = $this->mockStatement();
        $statement
            ->expects($this->once())
            ->method('execute')
            ->with(new ParameterConstraint([17]))
            ->willReturn(false);

        $connection = $this->getConnectionMock();
        $connection
            ->expects($this->once())
            ->method('prepare')
            ->with(new RegularExpression(self::DELETE))
            ->willReturn($statement);

        $foo = new Foo();
        $foo->setId(17);
        $foo->setField1('oValue1');
        $foo->setField2('oValue2');

        $manager = $this->create();

        $this->assertSame(
            false, $manager->remove($foo),
            "$this->class::persist() should return 'FAILURE' if query failed"
        );
        $this->assertSame(
            17, $foo->getId(),
            "$this->class::remove() method should not change the entity 'id' after failed deletion"
        );
    }
}
