<?php

namespace Form\Field;

/**
 * Class CheckboxField
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class CheckboxField extends AbstractField
{
    /**
     * @inheritdoc
     */
    public function convertToPhpValue($data)
    {
        $data = (bool)$data;

        if (!$data && $this->options['required']) {
            $this->throwRequiredFieldException();
        }

        return $data;
    }

    /**
     * @inheritdoc
     */
    public function convertToHtmlValue($data)
    {
        return (bool)$data;
    }

    /**
     * @inheritdoc
     */
    public function render($data): string
    {
        return
            '<div class="form-group form-check">' .
                $this->renderWidget($data) .
                $this->renderLabel() .
            '</div>';
    }

    /**
     * @inheritdoc
     */
    protected function renderWidget($data): string
    {
        $attributes = [
            'type'  => 'checkbox',
            'class' => 'form-check-input',
            'id'    => $this->name,
            'name'  => $this->name,
            'value' => 1,
        ];

        if ($data) {
            $attributes['checked'] = 'checked';
        }

        return sprintf(
            '<input%s>',
            $this->renderAttributes($attributes)
        );
    }

    /**
     * @inheritdoc
     */
    protected function resolveOptions(array $options): array
    {
        return array_replace([
            'required' => false,
            'disabled' => false,
        ], $options);
    }
}
