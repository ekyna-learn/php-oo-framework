<?php

namespace Http;

/**
 * Interface ControllerInterface
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface ControllerInterface
{
    /**
     * Handle the request.
     *
     * @param Request $request
     *
     * @return Response The response
     */
    public function handle(Request $request): Response;
}
