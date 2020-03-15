<?php

namespace Test\Http;

use InvalidArgumentException;
use Test\Acme\Controller\BarController;
use Test\Acme\Controller\FooController;
use Test\TestCase;

/**
 * Class RouterTest
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class RouterTest extends TestCase
{
    protected $class = 'Http\Router';
    protected $routeClass = 'Http\Route';


    public function test_properties(): void
    {
        $this->assertProperty('routes', self::PRIVATE);
    }

    public function test_methods(): void
    {
        $this->assertMethod('addRoute', self::PUBLIC);
        $this->assertMethod('match', self::PUBLIC, [], 'array');
        $this->assertMethod('generate', self::PUBLIC, [], 'string');
    }

    public function test_addRoute(): void
    {
        $router = $this->createRouter();

        $router->addRoute('foo', '/foo', FooController::class);

        $rp = $this->getReflectionProperty('routes');
        $rp->setAccessible(true);
        $routes = $rp->getValue($router);

        $this->assertCount(1, $routes);
        $this->assertContainsOnlyInstancesOf($this->routeClass, $routes);

        $this->assertRoute(reset($routes), 'foo', '/foo', FooController::class);
    }

    private function assertRoute($route, string $name, string $pattern, string $controller): void
    {
        $this->assertInstanceOf($this->routeClass, $route);
        /** @var \Http\Route $route */
        $this->assertEquals($name, $route->getName());
        $this->assertEquals($pattern, $route->getPattern());
        $this->assertEquals($controller, $route->getController());
    }

    public function test_addRoute_with_the_same_name(): void
    {
        $router = $this->createRouter();

        $router->addRoute('foo', '/foo', FooController::class);

        $this->expectException(\InvalidArgumentException::class);

        $router->addRoute('foo', '/bar', FooController::class);
    }

    /** @dataProvider provideMatch */
    public function test_match(array $routes, string $path, array $expected): void
    {
        $router = $this->createRouter();

        foreach ($routes as $route) {
            $router->addRoute($route[0], $route[1], $route[2]);
        }

        $this->assertEquals($expected, $router->match($path));
    }

    public function provideMatch(): array
    {
        return [
            'Case 1' => [
                'routes'     => [
                    ['foo', '/foo', FooController::class],
                ],
                'path'       => '/',
                'attributes' => [
                    '_controller' => null,
                ],
            ],
            'Case 2' => [
                'routes'     => [
                    ['foo', '/foo', FooController::class],
                    ['bar', '/bar', BarController::class],
                ],
                'path'       => '/foo',
                'attributes' => [
                    '_controller' => FooController::class,
                ],
            ],
            'Case 3' => [
                'routes'     => [
                    ['foo', '/foo', FooController::class],
                    ['bar', '/bar', BarController::class],
                ],
                'path'       => '/bar',
                'attributes' => [
                    '_controller' => BarController::class,
                ],
            ],
            'Case 4' => [
                'routes'     => [
                    ['foo', '/foo/{param1}', FooController::class],
                ],
                'path'       => '/foo/123',
                'attributes' => [
                    'param1'      => '123',
                    '_controller' => FooController::class,
                ],
            ],
            'Case 5' => [
                'routes'     => [
                    ['foo', '/foo/{param1}/path-1/{param2}/path-2', FooController::class],
                    ['bar', '/bar/{param3}/path-1/{param4}/path-2', BarController::class],
                ],
                'path'       => '/bar/123/path-1/some-test/path-2',
                'attributes' => [
                    'param3'      => '123',
                    'param4'      => 'some-test',
                    '_controller' => BarController::class,
                ],
            ],
        ];
    }

    /** @dataProvider provideGenerate */
    public function test_generate(array $routes, string $name, array $params, string $expected): void
    {
        $router = $this->createRouter();

        foreach ($routes as $route) {
            $router->addRoute($route[0], $route[1], $route[2]);
        }

        $this->assertEquals($expected, $router->generate($name, $params));
    }

    public function provideGenerate(): array
    {
        return [
            'Case 1' => [
                'routes'     => [
                    ['foo', '/foo', FooController::class],
                ],
                'name'       => 'foo',
                'parameters' => [],
                'path'       => '/foo',
            ],
            'Case 2' => [
                'routes'     => [
                    ['foo', '/foo', FooController::class],
                    ['bar', '/bar', BarController::class],
                ],
                'name'       => 'bar',
                'parameters' => [],
                'path'       => '/bar',
            ],
            'Case 3' => [
                'routes'     => [
                    ['foo', '/foo/{param1}', FooController::class],
                    ['bar', '/bar/{param2}', BarController::class],
                ],
                'name'       => 'foo',
                'parameters' => [
                    'param1' => 'test',
                ],
                'path'       => '/foo/test',
            ],
            'Case 5' => [
                'routes'     => [
                    ['foo', '/foo/{param1}/path-1/{param2}/path-2', FooController::class],
                    ['bar', '/bar/{param3}/path-1/{param4}/path-2', BarController::class],
                ],
                'name'       => 'bar',
                'parameters' => [
                    'param3' => 123,
                    'param4' => 'some-test',
                ],
                'path' => '/bar/123/path-1/some-test/path-2'
            ],
        ];
    }

    public function test_generate_unknown_route(): void
    {
        $router = $this->createRouter();

        $this->expectException(InvalidArgumentException::class);

        $router->generate('foo', []);
    }

    public function test_generate_missing_parameter(): void
    {
        $router = $this->createRouter();
        $router->addRoute('foo', '/{bar}', FooController::class);

        $this->expectException(InvalidArgumentException::class);

        $router->generate('foo', []);
    }

    /** @return \Http\Router */
    private function createRouter()
    {
        $this->skipIfClassDoesNotExist($this->routeClass);

        return parent::create();
    }
}
