<?php

namespace Form;

use Form\Field\FieldInterface;
use InvalidArgumentException;
use LogicException;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class Form
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class Form implements FormInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $action;

    /**
     * @var object
     */
    private $data;

    /**
     * @var FieldInterface[]
     */
    private $fields;

    /**
     * @var boolean
     */
    private $submitted;

    /**
     * @var PropertyAccess
     */
    private $accessor;


    /**
     * Constructor.
     *
     * @param string $name
     * @param string $action
     */
    public function __construct(string $name, string $action = null)
    {
        $this->name = $name;
        $this->action = $action;

        $this->fields = [];
        $this->submitted = false;

        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * Adds the field.
     *
     * @param FieldInterface $field The field instance
     *
     * @return Form
     */
    public function addField(FieldInterface $field): self
    {
        $name = $field->getName();

        if (array_key_exists($name, $this->fields)) {
            throw new InvalidArgumentException("Field '$name' is already defined.");
        }

        $this->fields[$name] = $field;

        return $this;
    }

    /**
     * Sets the action.
     *
     * @param string $action
     *
     * @return Form
     */
    public function setAction(string $action): FormInterface
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Sets the form data
     *
     * @param object $data
     *
     * @return FormInterface
     */
    public function setData(object $data): FormInterface
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function isSubmitted(): bool
    {
        return $this->submitted;
    }

    /**
     * @inheritDoc
     */
    public function bindRequest(array $request): void
    {
        $this->assertConfigured();

        if (array_key_exists($this->name, $request) && $request[$this->name] === $this->name) {
            $this->submitted = true;
        } else {
            return;
        }

        foreach ($this->fields as $name => $field) {
            if (array_key_exists($name, $request)) {
                $value = $field->convertToPhpValue($request[$name]);

                $this->accessor->setValue($this->data, $name, $value);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        $this->assertConfigured();

        $output =
            '<form action="' . $this->action . '" method="post">' .
                '<input type="hidden" name="' . $this->name . '" value="' . $this->name . '">';

        foreach ($this->fields as $name => $field) {
            $data = $this->accessor->getValue($this->data, $name);

            $value = $field->convertToHtmlValue($data);

            $output .= $field->render($value);
        }

        $output .=
                '<button type="submit" class="btn btn-primary">Submit</button>' .
            '</form>';

        return $output;
    }

    /**
     * Asserts that the form is configured.
     */
    private function assertConfigured()
    {
        if (null === $this->data) {
            throw new LogicException("Call Form::setData() first.");
        }
        if (empty($this->fields)) {
            throw new LogicException("Call Form::addField() first.");
        }
    }
}
