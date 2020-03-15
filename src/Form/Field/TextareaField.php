<?php

namespace Form\Field;

/**
 * Class TextareaField
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class TextareaField extends TextField
{
    /**
     * @inheritdoc
     */
    protected function renderWidget($data): string
    {
        $attributes = [
            'class' => 'form-control',
            'id'    => $this->name,
            'name'  => $this->name,
        ];

        return sprintf('<textarea%s>%s</textarea>', $this->renderAttributes($attributes), $data);
    }
}
