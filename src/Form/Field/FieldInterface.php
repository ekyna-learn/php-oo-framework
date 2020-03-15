<?php

namespace Form\Field;

/**
 * Interface FieldInterface
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface FieldInterface
{
    /**
     * Returns the field name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Returns the label.
     *
     * @return string
     */
    public function getLabel(): string;

    /**
     * Returns the options.
     *
     * @return array
     */
    public function getOptions(): array;

    /**
     * Transforms the HTML data (from the request) into a PHP data.
     *
     * @param mixed $data The html data
     *
     * @return mixed The PHP data
     */
    public function convertToPhpValue($data);

    /**
     * Transforms the PHP data into a HTML data (for form rendering).
     *
     * @param mixed $data
     *
     * @return mixed
     */
    public function convertToHtmlValue($data);

    /**
     * Renders the field's html.
     *
     * @param mixed $data The data to display
     *
     * @return string The rendered html.
     */
    public function render($data): string;
}
