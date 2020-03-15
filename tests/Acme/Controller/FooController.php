<?php

namespace Test\Acme\Controller;

use Http\ControllerInterface;
use Http\Request;
use Http\Response;

/**
 * Class FooController
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class FooController implements ControllerInterface
{
    public function handle(Request $request): Response
    {
        return new Response('Foo');
    }
}
