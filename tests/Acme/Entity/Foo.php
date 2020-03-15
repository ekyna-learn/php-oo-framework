<?php

namespace Test\Acme\Entity;

use Persistence\EntityInterface;

/**
 * Class Foo
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class Foo implements EntityInterface
{
    private $id;
    private $field1;
    private $field2;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id = null): void
    {
        $this->id = $id;
    }

    public function getField1()
    {
        return $this->field1;
    }

    public function setField1($field1)
    {
        $this->field1 = $field1;
    }

    public function getField2()
    {
        return $this->field2;
    }

    public function setField2($field2)
    {
        $this->field2 = $field2;
    }
}
