<?php

namespace Test\Persistence;

use PDO;
use PDOStatement;
use Persistence\ServiceInterface;
use Persistence\Mapping\MappingInterface;
use Persistence\Mapping\Property\PropertyInterface;
use Test\Acme\Entity\Foo;
use Test\TestCase;

/**
 * Class AbstractEntityServiceTest
 * @package Test\Persistence
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
abstract class AbstractEntityServiceTest extends TestCase
{
    protected const TABLE = '[a-zA-Z][a-zA-Z0-9_]*[a-zA-Z0-9]';

    protected const FIELD  = '[a-zA-Z][a-zA-Z0-9_]*[a-zA-Z0-9]';
    protected const FIELDS = '\*|(' . self::FIELD . '(\s*,\s*' . self::FIELD . ')*)';

    protected const PARAMETER  = ':' . self::FIELD;
    protected const PARAMETERS = self::PARAMETER . '(\s*,\s*' . self::PARAMETER . ')*';

    protected const ASSIGNMENT  = self::FIELDS . '\s*=\s*' . self::PARAMETER;
    protected const ASSIGNMENTS = self::ASSIGNMENT . '(\s*,\s*' . self::ASSIGNMENT . ')*';

    protected const LIMIT = '(\s+LIMIT\s+1\s*)?';
    protected const END   = '(\s*;)?';


    /** @var PDO */
    private $connection;

    /** @var MappingInterface */
    private $mapping;

    public function test_properties(): void
    {
        $this->assertProperty('connection', [self::PRIVATE, self::PROTECTED]);
        $this->assertProperty('mapping', [self::PRIVATE, self::PROTECTED]);
        $this->assertProperty('accessor', [self::PRIVATE, self::PROTECTED]);
    }

    public function test_methods(): void
    {
        $this->assertMethod('setConnection', self::PUBLIC, [
            ['name' => 'connection', 'type' => PDO::class],
        ]);
        $this->assertMethod('setMapping', self::PUBLIC, [
            ['name' => 'mapping', 'type' => MappingInterface::class],
        ]);
    }

    /** @return PDO|\PHPUnit\Framework\MockObject\MockObject */
    protected function getConnectionMock(): PDO
    {
        if ($this->connection) {
            return $this->connection;
        }

        return $this->connection = $this->createMock(PDO::class);
    }

    /** @return PDOStatement|\PHPUnit\Framework\MockObject\MockObject */
    protected function mockStatement(): PDOStatement
    {
        return $this->createMock(PDOStatement::class);
    }

    /** @return MappingInterface|\PHPUnit\Framework\MockObject\MockObject */
    protected function getMappingMock(): MappingInterface
    {
        if ($this->mapping) {
            return $this->mapping;
        }

        $map = [
            'field1' => [
                'iValue1' => 'oValue1',
            ],
            'field2' => [
                'iValue2' => 'oValue2',
            ],
        ];

        return $this->mapping = $this->mockMapping(Foo::class, 'foo', $map);
    }

    protected function mockMapping(string $class, string $table, array $propertyMap = []): MappingInterface
    {
        $properties = [];
        foreach ($propertyMap as $name => $dataSet) {
            $properties[$name] = $this->mockProperty($name, $dataSet);
        }

        $mapping = $this->createMock(MappingInterface::class);

        $mapping
            ->expects($this->any())
            ->method('getClass')
            ->willReturn($class);

        $mapping
            ->expects($this->any())
            ->method('getTable')
            ->willReturn($table);

        $mapping
            ->expects($this->any())
            ->method('getProperties')
            ->willReturn($properties);

        $mapping
            ->expects($this->any())
            ->method('getRepositoryClass')
            ->willReturn('Persistence\Repository\EntityRepository');

        $mapping
            ->expects($this->any())
            ->method('getManagerClass')
            ->willReturn('Persistence\Manager\EntityManager');

        return $mapping;
    }

    /** @return PropertyInterface|\PHPUnit\Framework\MockObject\MockObject */
    private function mockProperty(string $name, array $dataSet): PropertyInterface
    {
        $property = $this->createMock(PropertyInterface::class);

        $property
            ->expects($this->any())
            ->method('getName')
            ->willReturn($name);

        foreach ($dataSet as $iData => $oData) {
            $property
                ->expects($this->any())
                ->method('convertToPhpValue')
                ->with($iData)
                ->willReturn($oData);

            $property
                ->expects($this->any())
                ->method('convertToDatabaseValue')
                ->with($oData)
                ->willReturn($iData);
        }

        return $property;
    }

    protected function create(...$args)
    {
        /** @var ServiceInterface $repository */
        $repository = parent::create($args);

        $repository->setConnection($this->getConnectionMock());
        $repository->setMapping($this->getMappingMock());

        return $repository;
    }
}
