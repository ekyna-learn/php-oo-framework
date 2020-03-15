<?php

namespace Form;

/**
 * Interface FormInterface
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
interface FormInterface
{
    /**
     * Returns whether the form is submitted.
     *
     * @return bool
     */
    public function isSubmitted(): bool;

    /**
     * Binds the request data.
     *
     * @param array $request The request data
     */
    public function bindRequest(array $request): void;

    /**
     * Renders the form.
     *
     * @return string
     */
    public function render(): string;
}
