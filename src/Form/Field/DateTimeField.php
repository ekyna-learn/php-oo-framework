<?php

namespace Form\Field;

use DateTime;
use Exception;
use RuntimeException;

/**
 * Class DateTimeField
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class DateTimeField extends AbstractField
{
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

        try {
            $transformed = new DateTime($data);
        } catch (Exception $e) {
            throw new RuntimeException("Failed to transform the request data into a date time object.");
        }

        return $transformed;
    }

    /**
     * @inheritdoc
     */
    public function convertToHtmlValue($data)
    {
        if ($data instanceof DateTime) {
            return $data->format('Y-m-d');
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    protected function renderWidget($data): string
    {
        $attributes = [
            'type'        => 'date',
            'class'       => 'form-control',
            'id'          => $this->name,
            'name'        => $this->name,
            'placeholder' => $this->label,
            'value'       => $data,
        ];

        return sprintf('<input%s>', $this->renderAttributes($attributes));
    }
}
