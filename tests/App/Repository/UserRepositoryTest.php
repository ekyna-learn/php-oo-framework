<?php

namespace Test\App\Repository;

use PDO;
use Test\Database;
use Test\DatabaseTestCase;

/**
 * Class UserRepositoryTest
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class UserRepositoryTest extends DatabaseTestCase
{
    protected $class = 'App\Repository\UserRepository';
    private $userClass = 'App\Entity\User';

    public function test_constructor(): void
    {
        $this->assertConstructor([['name' => 'connection', 'type' => PDO::class]]);

        $this->assertInstanceOf($this->class, $this->createRepository());
    }

    public function test_findOneById(): void
    {
        $this->assertMethod('findOneById', self::PUBLIC, [['name' => 'id', 'type' => 'int']], $this->userClass, true);

        Database::loadDataset('dataset1');

        $repository = $this->createRepository();

        $this->assertJohnDoe($repository->findOneById(1));
        $this->assertJaneDoe($repository->findOneById(2));
    }

    public function test_findOneByEmail(): void
    {
        $this->assertMethod('findOneByEmail', self::PUBLIC, [['name' => 'email', 'type' => 'string']], $this->userClass, true);

        Database::loadDataset('dataset1');

        $repository = $this->createRepository();

        $this->assertJohnDoe($repository->findOneByEmail('john.doe@example.org'));
        $this->assertJaneDoe($repository->findOneByEmail('jane.doe@example.org'));
    }

    public function test_findAll(): void
    {
        $this->assertMethod('findAll', self::PUBLIC, [], 'array');

        Database::loadDataset('dataset1');

        $users = $this->createRepository()->findAll();

        $this->assertCount(2, $users);

        $this->assertJohnDoe($users[0]);
        $this->assertJaneDoe($users[1]);
    }

    /** @param \App\Entity\User $user */
    private function assertJohnDoe($user): void
    {
        $this->assertInstanceOf($this->userClass, $user);

        $this->assertEquals(
            1,
            $user->getId(),
            'App\Entity\User::id is not hydrated properly'
        );

        $this->assertEquals(
            'john.doe@example.org',
            $user->getEmail(),
            'App\Entity\User::email is not hydrated properly'
        );

        $this->assertEquals(
            '6c074fa94c98638dfe3e3b74240573eb128b3d16',
            $user->getPassword(),
            'App\Entity\User::password is not hydrated properly'
        );

        $this->assertEquals(
            'John Doe',
            $user->getName(),
            'App\Entity\User::name is not hydrated properly'
        );

        $this->assertEquals(
            new \DateTime('2000-01-01'),
            $user->getBirthday(),
            'App\Entity\User::birthday is not hydrated properly'
        );

        $this->assertTrue(
            $user->isActive(),
            'App\Entity\User::active is not hydrated properly'
        );
    }

    /** @param \App\Entity\User $user */
    private function assertJaneDoe($user): void
    {
        $this->assertInstanceOf($this->userClass, $user);

        $this->assertEquals(
            2,
            $user->getId(),
            'App\Entity\User::id is not hydrated properly'
        );

        $this->assertEquals(
            'jane.doe@example.org',
            $user->getEmail(),
            'App\Entity\User::email is not hydrated properly'
        );

        $this->assertEquals(
            '06d213088a72f4c1ac947c6f3d9ddd321650ebfb',
            $user->getPassword(),
            'App\Entity\User::password is not hydrated properly'
        );

        $this->assertEquals(
            'Jane Doe',
            $user->getName(),
            'App\Entity\User::name is not hydrated properly'
        );

        $this->assertNull(
            $user->getBirthday(),
            'App\Entity\User::birthday is not hydrated properly'
        );

        $this->assertFalse(
            $user->isActive(),
            'App\Entity\User::active is not hydrated properly'
        );
    }

    /**
     * @return \App\Repository\UserRepository
     */
    private function createRepository()
    {
        $this->skipIfClassDoesNotExist($this->userClass);

        return parent::create(Database::getConnection());
    }
}
