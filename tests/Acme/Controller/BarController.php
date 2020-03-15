<?php

namespace Test\Acme\Controller;

use Http\ControllerInterface;
use Http\Request;
use Http\Response;

/**
 * Class BarController
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class BarController implements ControllerInterface
{
    public function handle(Request $request): Response
    {
        return new Response('Bar');
    }
}
