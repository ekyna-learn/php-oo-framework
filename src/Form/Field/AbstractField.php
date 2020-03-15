<?php

namespace Form\Field;

use InvalidArgumentException;

/**
 * Class AbstractField
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
abstract class AbstractField implements FieldInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var array
     */
    protected $options;


    /**
     * Constructor.
     *
     * @param string $name
     * @param string $label
     * @param array  $options
     */
    public function __construct(string $name, string $label = null, array $options = [])
    {
        $this->name = $name;
        $this->label = $label ?? $name;
        $this->options = $this->resolveOptions($options);
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @inheritdoc
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @inheritdoc
     */
    public function convertToPhpValue($data)
    {
        $data = trim($data);

        if (empty($data)) {
            if ($this->options['required']) {
                $this->throwRequiredFieldException();
            }

            return null;
        }

        return $data;
    }

    /**
     * @inheritdoc
     */
    public function convertToHtmlValue($data)
    {
        return trim($data);
    }

    /**
     * Renders the field group by wrapping the given widget html.
     *
     * @param mixed $data The field data
     *
     * @return string
     */
    public function render($data): string
    {
        return
            '<div class="form-group">' .
                $this->renderLabel() .
                $this->renderWidget($data) .
            '</div>';
    }

    /**
     * Renders the field label.
     *
     * @return string
     */
    protected function renderLabel(): string
    {
        return '<label for="' . $this->name . '">' . $this->label . '</label>';
    }

    /**
     * Renders the widget.
     *
     * @param mixed $data The field data
     *
     * @return string
     */
    abstract protected function renderWidget($data): string;

    /**
     * Builds the html tag's attributes.
     *
     * @param array $attributes
     *
     * @return string
     */
    protected function renderAttributes(array $attributes): string
    {
        // Add 'required' attribute if enabled
        if ($this->options['required']) {
            $attributes['required'] = 'required';
        }

        // Add 'disabled' attribute if enabled
        if ($this->options['disabled']) {
            $attributes['disabled'] = 'disabled';
        }

        // Render attributes
        $output = '';
        foreach ($attributes as $key => $value) {
            $output .= " $key=\"$value\"";
        }

        return $output;
    }

    /**
     * Throws an exception when value is not provided for a required field.
     */
    protected function throwRequiredFieldException()
    {
        throw new InvalidArgumentException("The field '$this->name' is required.");
    }

    /**
     * Resolves the type options.
     *
     * @param array $options
     *
     * @return array
     */
    protected function resolveOptions(array $options): array
    {
        return array_replace([
            'required' => true,
            'disabled' => false,
        ], $options);
    }
}
