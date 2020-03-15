<?php

namespace Http;

/**
 * Interface RouterInterface
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface RouterInterface
{
    /**
     * Adds the route.
     *
     * @param string $name
     * @param string $pattern
     * @param string $controller
     *
     * @return RouterInterface
     */
    public function addRoute(string $name, string $pattern, string $controller);

    /**
     * Tries to match a path path with a set of routes, and returns the parameters.
     *
     * @param string $path
     *
     * @return array
     */
    public function match(string $path): array;

    /**
     * Generates a path for a specific route based on the given parameters.
     *
     * @param string $name
     * @param array  $parameters
     *
     * @return string
     */
    public function generate(string $name, array $parameters = []): string;
}
