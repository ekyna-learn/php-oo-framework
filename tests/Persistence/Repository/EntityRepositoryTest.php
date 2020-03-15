<?php

namespace Test\Persistence\Repository;

use PDO;
use Persistence\EntityInterface;
use Persistence\Repository\RepositoryInterface;
use PHPUnit\Framework\Constraint\RegularExpression;
use Test\Acme\Entity\Foo;
use Test\Persistence\AbstractEntityServiceTest;

/**
 * Class EntityRepositoryTest
 * @author Étienne Dauvergne <contact@ekyna.com>
 *
 * @method RepositoryInterface create(...$args)
 */
class EntityRepositoryTest extends AbstractEntityServiceTest
{
    private const ALL =
        '~^SELECT\s+' . self::FIELDS . '\s+FROM\s+' . self::TABLE . '~';

    private const ONE =
        '~^SELECT\s+' . self::FIELDS .
        '\s+FROM\s+' . self::TABLE .
        '\s+WHERE\s+' . self::ASSIGNMENT .
        self::LIMIT . self::END . '$~';

    protected $class = 'Persistence\Repository\EntityRepository';

    public function assert_methods(): void
    {
        parent::assert_methods();

        $this->assertMethod('findAll', self::PUBLIC, [], 'array');

        $this->assertMethod('findOneById', self::PUBLIC, [
            ['name' => 'id', 'type' => 'int'],
        ], EntityInterface::class, true);

        $this->assertMethod('hydrate', self::PROTECTED, [
            ['name' => 'id', 'type' => EntityInterface::class],
            ['name' => 'data', 'type' => 'array'],
        ]);
    }

    public function testFindAll_withResults(): void
    {
        $this->skipIfMethodIsNotDefined('setConnection');
        $this->skipIfMethodIsNotDefined('setMapping');
        $this->skipIfMethodIsNotDefined('findAll');

        $repository = $this->create();

        $statement = $this->mockStatement();
        $statement
            ->expects($this->exactly(2))
            ->method('fetch')
            ->with(PDO::FETCH_ASSOC)
            ->willReturnOnConsecutiveCalls([
                'id'     => 10,
                'field1' => 'iValue1',
                'field2' => 'iValue2',
            ], false);

        $connection = $this->getConnectionMock();
        $connection
            ->expects($this->once())
            ->method('query')
            ->with(new RegularExpression(self::ALL))
            ->willReturn($statement);

        $result = $repository->findAll();

        $this->assertContainsOnlyInstancesOf(
            Foo::class, $result,
            "$this->class::findAll() method should return an array of " .
            EntityInterface::class . " if there are database records"
        );

        /** @var Foo $entity */
        $entity = array_shift($result);
        $this->assertSame(10, $entity->getId());
        $this->assertSame('oValue1', $entity->getField1());
        $this->assertSame('oValue2', $entity->getField2());
    }

    public function testFindAll_withoutResults(): void
    {
        $this->skipIfMethodIsNotDefined('setConnection');
        $this->skipIfMethodIsNotDefined('setMapping');
        $this->skipIfMethodIsNotDefined('findAll');

        $repository = $this->create();

        $statement = $this->mockStatement();
        $statement
            ->expects($this->once())
            ->method('fetch')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn(false);

        $connection = $this->getConnectionMock();
        $connection
            ->expects($this->once())
            ->method('query')
            ->with(new RegularExpression(self::ALL))
            ->willReturn($statement);

        $result = $repository->findAll();

        $this->assertSame(
            [], $result,
            "$this->class::findAll() method should return an empty array " .
            "if there is no database record"
        );
    }

    public function testFindOneById_withResult(): void
    {
        $this->skipIfMethodIsNotDefined('setConnection');
        $this->skipIfMethodIsNotDefined('setMapping');
        $this->skipIfMethodIsNotDefined('findAll');

        $repository = $this->create();

        $statement = $this->mockStatement();
        $statement
            ->expects($this->once())
            ->method('fetch')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn([
                'id'     => 11,
                'field1' => 'iValue1',
                'field2' => 'iValue2',
            ]);

        $connection = $this->getConnectionMock();
        $connection
            ->expects($this->once())
            ->method('query')
            ->with(new RegularExpression(self::ONE))
            ->willReturn($statement);

        /** @var Foo $entity */
        $entity = $repository->findOneById(11);

        $this->assertInstanceOf(
            Foo::class, $entity,
            "$this->class::findOneById() method should return instance of " .
            EntityInterface::class . " if 'id' argument value matches a database record."
        );

        $this->assertSame(11, $entity->getId());
        $this->assertSame('oValue1', $entity->getField1());
        $this->assertSame('oValue2', $entity->getField2());
    }

    public function testFindOneById_withoutResult(): void
    {
        $this->skipIfMethodIsNotDefined('setConnection');
        $this->skipIfMethodIsNotDefined('setMapping');
        $this->skipIfMethodIsNotDefined('findAll');

        $repository = $this->create();

        $statement = $this->mockStatement();
        $statement
            ->expects($this->once())
            ->method('fetch')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn(false);

        $connection = $this->getConnectionMock();
        $connection
            ->expects($this->once())
            ->method('query')
            ->with(new RegularExpression(self::ONE))
            ->willReturn($statement);

        /** @var Foo $entity */
        $entity = $repository->findOneById(11);

        $this->assertNull(
            $entity,
            "$this->class::findOneById() method should return null if " .
            "'id' argument value does not match any database record."
        );
    }
}
