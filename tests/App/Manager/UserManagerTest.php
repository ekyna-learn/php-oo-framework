<?php

namespace Test\App\Manager;

use PDO;
use Test\Database;
use Test\DatabaseTestCase;

/**
 * Class UserManagerTest
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class UserManagerTest extends DatabaseTestCase
{
    protected $class     = 'App\Manager\UserManager';
    private   $userClass = 'App\Entity\User';

    public function test_constructor(): void
    {
        $this->assertConstructor([['name' => 'connection', 'type' => PDO::class]]);
    }

    public function test_methods(): void
    {
        $this->assertMethod('persist', self::PUBLIC, [['name' => 'user', 'type' => $this->userClass]]);
        $this->assertMethod('remove', self::PUBLIC, [['name' => 'user', 'type' => $this->userClass]]);
    }

    public function test_persist(): void
    {
        $this->skipIfMethodIsNotDefined('persist');

        $manager = $this->createManager();

        $map = [
            'id'       => 1,
            'email'    => 'test@example.org',
            'password' => 'ABC123',
            'name'     => 'Mr Test',
            'birthday' => '2020-01-01',
            'active'   => 1,
        ];

        /** @var \App\Entity\User $user */
        $user = new $this->userClass();
        $user
            ->setEmail($map['email'])
            ->setPassword($map['password'])
            ->setName($map['name'])
            ->setBirthday(new \DateTime($map['birthday']))
            ->setActive($map['active']);

        $manager->persist($user);

        $this->assertEquals(1, $user->getId());

        $statement = Database::getConnection()->query('SELECT * FROM user WHERE id=1 LIMIT 1');
        $data = $statement->fetch(\PDO::FETCH_ASSOC);
        $this->assertNotFalse($data, "$this->class::insert() does not insert user in database.");

        foreach ($map as $field => $value) {
            $this->assertEquals(
                $value, $data[$field],
                sprintf("%s::insert() should insert property '%s' value in database", $this->class, $field)
            );
        }
    }

    public function test_update(): void
    {
        $this->skipIfMethodIsNotDefined('update');

        Database::loadDataset('dataset1');

        $manager = $this->createManager();

        $map = [
            'id'       => 1,
            'email'    => 'test@example.org',
            'password' => 'ABC123',
            'name'     => 'Mr Test',
            'birthday' => null,
            'active'   => 0,
        ];

        /** @var \App\Entity\User $user */
        $user = new $this->userClass();
        $user->setId($map['id']);
        $user
            ->setEmail($map['email'])
            ->setPassword($map['password'])
            ->setName($map['name'])
            ->setBirthday($map['birthday'])
            ->setActive($map['active']);

        $manager->persist($user);

        $statement = Database::getConnection()->query('SELECT * FROM user WHERE id=1 LIMIT 1');
        $data = $statement->fetch(\PDO::FETCH_ASSOC);
        $this->assertNotFalse($data, "$this->class::insert() should insert user in database.");

        foreach ($map as $field => $value) {
            $this->assertEquals(
                $value, $data[$field],
                sprintf("%s::insert() should insert property '%s' value in database", $this->class, $field)
            );
        }
    }

    public function test_remove():void
    {
        $this->skipIfMethodIsNotDefined('remove');

        /** @var \App\Entity\User $user */
        $user = new $this->userClass();
        $user->setId(1);

        $manager = $this->createManager();

        $manager->remove($user);

        $this->assertNull($user->getId(), "$this->class::remove should set user id to NULL.");

        $statement = Database::getConnection()->query('SELECT * FROM user WHERE id=1 LIMIT 1');

        $this->assertFalse(
            $statement->fetch(\PDO::FETCH_ASSOC),
            "$this->class::remove() should remove the user from the database."
        );
    }

    /** @return \App\Manager\UserManager */
    private function createManager()
    {
        $this->skipIfClassDoesNotExist($this->userClass);

        return parent::create(Database::getConnection());
    }
}
