<?php

namespace Test\Http;

use Test\TestCase;

/**
 * Class RequestTest
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class RequestTest extends TestCase
{
    protected $class    = 'Http\Request';
    private   $bagClass = 'Http\ParameterBag';

    public function test_properties(): void
    {
        $this->assertProperty('get', self::PUBLIC);
        $this->assertProperty('post', self::PUBLIC);
        $this->assertProperty('parameters', self::PUBLIC);
        $this->assertProperty('path', self::PRIVATE);
    }

    public function test_constructor(): void
    {
        $request = $this->createRequest('/foo');

        $rp = $this->getReflectionProperty('path');
        $rp->setAccessible(true);
        $this->assertEquals('/foo', $rp->getValue($request), 'Http\Request::path is not properly initialized');

        $emptyParameterBag = new $this->bagClass([]);

        $this->assertInstanceOf($this->bagClass, $request->get);
        $this->assertEquals($emptyParameterBag, $request->get);

        $this->assertInstanceOf($this->bagClass, $request->post);
        $this->assertEquals($emptyParameterBag, $request->post);

        $this->assertInstanceOf($this->bagClass, $request->parameters);
        $this->assertEquals($emptyParameterBag, $request->parameters);
    }

    public function test_getPath(): void
    {
        $this->assertMethod('getPath', self::PUBLIC, [], 'string');

        $request = $this->createRequest('/foo');

        $this->assertEquals('/foo', $request->getPath());
    }

    public function test_createFromGlobals(): void
    {
        $this->skipIfClassDoesNotExist($this->bagClass);

        $this->assertMethod('createFromGlobals', self::PUBLIC, [], $this->class, false, true);

        $_SERVER['REQUEST_URI'] = '/foo';
        $_GET = ['foo' => 'test1'];
        $_POST = ['bar' => 'test2'];

        $request = call_user_func([$this->class, 'createFromGlobals']);

        $this->assertInstanceOf($this->class, $request);

        $this->assertEquals('/foo', $request->getPath());

        $this->assertInstanceOf($this->bagClass, $request->get);
        $this->assertEquals('test1', $request->get->get('foo'));

        $this->assertInstanceOf($this->bagClass, $request->get);
        $this->assertEquals('test2', $request->post->get('bar'));

        $this->assertInstanceOf($this->bagClass, $request->get);
        $this->assertEquals(new $this->bagClass([]), $request->parameters);
    }

    /** @return \Http\Request */
    private function createRequest(string $path = '/foo')
    {
        $this->skipIfClassDoesNotExist($this->bagClass);

        return parent::create($path);
    }
}
