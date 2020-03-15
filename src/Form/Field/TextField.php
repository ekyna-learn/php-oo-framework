<?php

namespace Form\Field;

/**
 * Class TextField
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class TextField extends AbstractField
{
    /**
     * @inheritdoc
     */
    protected function renderWidget($data): string
    {
        $attributes = [
            'type'        => 'text',
            'class'       => 'form-control',
            'id'          => $this->name,
            'name'        => $this->name,
            'placeholder' => $this->label,
            'value'       => $data,
        ];

        return sprintf('<input%s>', $this->renderAttributes($attributes));
    }
}
