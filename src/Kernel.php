<?php

use Http\RedirectResponse;
use Http\Request;
use Http\Response;
use Service\Container;

/**
 * Class Kernel
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class Kernel
{
    /**
     * Handles the request.
     *
     * @param Request $request
     */
    public function handle(Request $request): void
    {
        $container = Container::getInstance();

        /** @var \Http\Router $router */
        $router = $container->get('router');

        $attributes = $router->match($request->getPath());

        if (!isset($attributes['_controller'])) {
            http_response_code(Response::HTTP_NOT_FOUND);

            return;
        }

        foreach ($attributes as $key => $value) {
            $request->parameters->set($key, $value);
        }

        /** @var \Http\ControllerInterface $controller */
        $controller = new $attributes['_controller']();

        $response = $controller->handle($request);

        http_response_code($response->getCode());

        if ($response instanceof RedirectResponse) {
            header('Location: ' . $response->getContent());

            return;
        }

        echo $response->getContent();
    }
}
