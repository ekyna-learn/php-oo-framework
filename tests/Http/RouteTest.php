<?php

namespace Test\Http;

use InvalidArgumentException;
use Test\Acme\Controller\FooController;
use Test\Acme\Controller\InvalidController;
use Test\TestCase;

/**
 * Class RouteTest
 * @author  Étienne Dauvergne <contact@ekyna.com>
 *
 * @method \Http\Route create()
 */
class RouteTest extends TestCase
{
    protected $class = 'Http\Route';

    public function test_properties(): void
    {
        $this->assertProperty('name', self::PRIVATE);
        $this->assertProperty('pattern', self::PRIVATE);
        $this->assertProperty('controller', self::PRIVATE);
        $this->assertProperty('compiled', self::PRIVATE);
    }

    public function test_methods(): void
    {
        $this->assertMethod('getName', self::PUBLIC, [], 'string');
        $this->assertMethod('setName', self::PUBLIC);
        $this->assertMethod('getPattern', self::PUBLIC, [], 'string');
        $this->assertMethod('setPattern', self::PUBLIC);
        $this->assertMethod('getController', self::PUBLIC, [], 'string');
        $this->assertMethod('setController', self::PUBLIC);
        $this->assertMethod('compile', self::PUBLIC, [], 'array');
    }

    /** @dataProvider provideInvalidNames */
    public function test_invalid_name(string $name): void
    {
        $this->expectException(InvalidArgumentException::class);

        $route = $this->create();
        $route->setName($name);
    }

    public function provideInvalidNames(): array
    {
        return [
            [''],
            ['_az'],
            ['az_'],
            ['_foo_bar_'],
        ];
    }

    /** @dataProvider provideValidNames */
    public function test_valid_name(string $name): void
    {
        $route = $this->create();
        $route->setName($name);
        $this->assertEquals($name, $route->getName());
    }

    public function provideValidNames(): array
    {
        return [
            ['foo'],
            ['foo_bar'],
            ['foo_bar2'],
            ['fo1_ba2_test'],
        ];
    }

    /** @dataProvider providePatterns */
    public function test_it_trims_pattern_slashes(string $pattern, string $expected): void
    {
        $route = $this->create();
        $route->setPattern($pattern);
        $this->assertEquals($expected, $route->getPattern());
    }

    public function providePatterns(): array
    {
        return [
            ['', '/'],
            ['//', '/'],
            ['path', '/path'],
            ['/path', '/path'],
            ['path/path/path', '/path/path/path'],
            ['path/{var}/path/', '/path/{var}/path'],
        ];
    }

    /** @dataProvider provideInvalidControllers */
    public function test_invalid_controller($controller): void
    {
        $this->expectException(InvalidArgumentException::class);

        $route = $this->create();
        $route->setController($controller);
    }

    public function provideInvalidControllers(): array
    {
        return [
            'empty'      => [''],
            'not_exist'  => ['invalid'],
            'implements' => [InvalidController::class],
        ];
    }

    /** @dataProvider provideValidControllers */
    public function test_valid_controller($controller): void
    {
        $route = $this->create();
        $route->setController($controller);
        $this->assertEquals($controller, $route->getController());
    }

    public function provideValidControllers(): array
    {
        return [
            'valid' => [FooController::class],
        ];
    }

    /**
     * @param \Http\Route $route
     *
     * @dataProvider provideInvalidRoute
     */
    public function test_compile_invalid_route($route): void
    {
        $this->skipIfClassDoesNotExist();

        $this->expectException(InvalidArgumentException::class);

        $route->compile();
    }

    public function provideInvalidRoute(): array
    {
        if (!class_exists($this->class)) {
            return [];
        }

        return [
            'name'       => [$this->create()->setPattern('/path')->setController(FooController::class)],
            'pattern'    => [$this->create()->setName('foo')->setController(FooController::class)],
            'controller' => [$this->create()->setName('foo')->setPattern('/path')],
        ];
    }

    /** @dataProvider provideCompiled */
    public function test_compile_valid_route(string $pattern, array $expected): void
    {
        $route = $this->create();
        $route
            ->setName('foo')
            ->setPattern($pattern)
            ->setController(FooController::class);

        $this->assertEquals($expected, $route->compile());
    }

    public function provideCompiled(): array
    {
        return [
            'Case 1' => [
                '/',
                [
                    'regex'     => '~^/$~',
                    'tokens'    => [['text', '/']],
                    'variables' => [],
                ],
            ],
            'Case 2' => [
                '/path1/path2',
                [
                    'regex'     => '~^/path1/path2$~',
                    'tokens'    => [['text', '/path1/path2']],
                    'variables' => [],
                ],
            ],
            'Case 3' => [
                '/path-1/{parameter1}/path_2',
                [
                    'regex'     => '~^/path-1/(?P<parameter1>[a-zA-Z0-9-_]+)/path_2$~',
                    'tokens'    => [
                        ['text', '/path-1/'],
                        ['variable', 'parameter1'],
                        ['text', '/path_2'],
                    ],
                    'variables' => ['parameter1'],
                ],
            ],
            'Case 4' => [
                '/path1/{parameter1}/path2/{parameter2}/3rd-path',
                [
                    'regex'     => '~^/path1/(?P<parameter1>[a-zA-Z0-9-_]+)/path2/(?P<parameter2>[a-zA-Z0-9-_]+)/3rd-path$~',
                    'tokens'    => [
                        ['text', '/path1/'],
                        ['variable', 'parameter1'],
                        ['text', '/path2/'],
                        ['variable', 'parameter2'],
                        ['text', '/3rd-path'],
                    ],
                    'variables' => ['parameter1', 'parameter2'],
                ],
            ],
        ];
    }
}
