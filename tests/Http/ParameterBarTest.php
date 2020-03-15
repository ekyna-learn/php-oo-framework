<?php

namespace Test\Http;

use Test\TestCase;

/**
 * Class ParameterBarTest
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ParameterBarTest extends TestCase
{
    private const DEFAULTS = [
        'foo' => 'bar',
    ];

    protected $class = 'Http\ParameterBag';

    public function test_properties(): void
    {
        $this->assertProperty('data', self::PRIVATE);
    }

    public function test_construct(): void
    {
        $rp = $this->getReflectionProperty('data');

        $rp->setAccessible(true);

        $bag = $this->createBag();

        $this->assertEquals(self::DEFAULTS, $rp->getValue($bag));
    }

    public function test_all(): void
    {
        $this->assertMethod('has', self::PUBLIC, [], 'bool');

        $bag = $this->createBag();

        $this->assertEquals(self::DEFAULTS, $bag->all());
    }

    public function test_has(): void
    {
        $this->assertMethod('has', self::PUBLIC, [], 'bool');

        $bag = $this->createBag();

        $this->assertTrue($bag->has('foo'));
        $this->assertFalse($bag->has('bar'));
    }

    public function test_get(): void
    {
        $this->assertMethod('get', self::PUBLIC);

        $bag = $this->createBag();

        $this->assertEquals('bar', $bag->get('foo'));
        $this->assertNull($bag->get('bar'));
        $this->assertEquals('foo', $bag->get('bar', 'foo'));
    }

    public function test_set(): void
    {
        $this->assertMethod('set', self::PUBLIC);

        $bag = $this->createBag();

        $bag->set('foo', 'baz');
        $this->assertEquals('baz', $bag->get('foo'));
        $bag->set('bar', 'test');
        $this->assertEquals('test', $bag->get('bar'));
    }

    public function test_clear(): void
    {
        $this->assertMethod('clear', self::PUBLIC);

        $bag = $this->createBag();

        $bag->clear('foo');
        $this->assertFalse($bag->has('foo'));
        $this->assertNull($bag->get('foo'));
    }

    /** @return \Http\ParameterBag */
    private function createBag(array $data = self::DEFAULTS)
    {
        return parent::create($data);
    }
}
