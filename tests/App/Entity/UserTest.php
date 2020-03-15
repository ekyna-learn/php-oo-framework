<?php

namespace Test\App\Entity;

use DateTime;
use Persistence\EntityInterface;
use Test\TestCase;

/**
 * Class UserTest
 * @author Étienne Dauvergne <contact@ekyna.com>
 *
 * @method \App\Entity\User create()
 */
class UserTest extends TestCase
{
    protected $class = 'App\Entity\User';

    public function test_class(): void
    {
        $reflection = $this->getReflectionClass();

        $this->assertTrue(
            $reflection->implementsInterface(EntityInterface::class),
            "Class $this->class must implement " . EntityInterface::class
        );
    }

    public function test_properties(): void
    {
        $this->assertProperty('id', self::PRIVATE);
        $this->assertProperty('email', self::PRIVATE);
        $this->assertProperty('password', self::PRIVATE);
        $this->assertProperty('plainPassword', self::PRIVATE);
        $this->assertProperty('name', self::PRIVATE);
        $this->assertProperty('birthday', self::PRIVATE);
        $this->assertProperty('active', self::PRIVATE);
    }

    public function test_methods(): void
    {
        $this->assertMethod('setId', self::PUBLIC, [['name' => 'id', 'type' => 'int', 'nullable' => true]]);
        $this->assertMethod('getId', self::PUBLIC, [], 'int', true);

        $this->assertMethod('setEmail', self::PUBLIC, [['name' => 'email', 'type' => 'string']]);
        $this->assertMethod('getEmail', self::PUBLIC, [], 'string', true);

        $this->assertMethod('setPassword', self::PUBLIC, [['name' => 'password', 'type' => 'string']]);
        $this->assertMethod('getPassword', self::PUBLIC, [], 'string', true);

        $this->assertMethod('setPlainPassword', self::PUBLIC, [['type' => 'string', 'nullable' => true]]);
        $this->assertMethod('getPlainPassword', self::PUBLIC, [], 'string', true);

        $this->assertMethod('setName', self::PUBLIC, [['name' => 'name', 'type' => 'string']]);
        $this->assertMethod('getName', self::PUBLIC, [], 'string', true);

        $this->assertMethod('setBirthday', self::PUBLIC,
            [['name' => 'birthday', 'type' => DateTime::class, 'nullable' => true]]);
        $this->assertMethod('getBirthday', self::PUBLIC, [], DateTime::class, true);

        $this->assertMethod('setActive', self::PUBLIC, [['name' => 'active', 'type' => 'bool']]);
        $this->assertMethod('isActive', self::PUBLIC, [], 'bool');
    }

    public function test_id(): void
    {
        $this->skipIfMethodIsNotDefined('setId');
        $this->skipIfMethodIsNotDefined('getId');

        $user = $this->create();

        $user->setId(123);
        $this->assertEquals(123, $user->getId());
    }

    public function test_email(): void
    {
        $this->skipIfMethodIsNotDefined('setEmail');
        $this->skipIfMethodIsNotDefined('getEmail');

        $user = $this->create();

        $user->setEmail('test@example.org');
        $this->assertEquals('test@example.org', $user->getEmail());
    }

    public function test_password(): void
    {
        $this->skipIfMethodIsNotDefined('setPassword');
        $this->skipIfMethodIsNotDefined('getPassword');

        $user = $this->create();

        $user->setPassword('ABC132');
        $this->assertEquals('ABC132', $user->getPassword());
    }

    public function test_plainPassword(): void
    {
        $this->skipIfMethodIsNotDefined('setPlainPassword');
        $this->skipIfMethodIsNotDefined('getPlainPassword');

        $user = $this->create();

        $user->setPlainPassword('ABC132');
        $this->assertEquals('ABC132', $user->getPlainPassword());

        $user->setPlainPassword(null);
        $this->assertNull($user->getPlainPassword());
    }

    public function test_name(): void
    {
        $this->skipIfMethodIsNotDefined('setName');
        $this->skipIfMethodIsNotDefined('getName');

        $user = $this->create();

        $user->setName('Mr Test');
        $this->assertEquals('Mr Test', $user->getName());
    }

    public function test_birthday(): void
    {
        $this->skipIfMethodIsNotDefined('setBirthday');
        $this->skipIfMethodIsNotDefined('getBirthday');

        $user = $this->create();

        $user->setBirthday($date = new DateTime());
        $this->assertEquals($date, $user->getBirthday());

        $user->setBirthday(null);
        $this->assertNull($user->getBirthday());
    }

    public function test_active(): void
    {
        $this->skipIfMethodIsNotDefined('setActive');
        $this->skipIfMethodIsNotDefined('isActive');

        $user = $this->create();

        $user->setActive(true);
        $this->assertTrue($user->isActive());

        $user->setActive(false);
        $this->assertFalse($user->isActive());
    }
}
