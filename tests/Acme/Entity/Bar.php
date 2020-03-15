<?php

namespace Test\Acme\Entity;

use Persistence\EntityInterface;

/**
 * Class Bar
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class Bar implements EntityInterface
{
    /** @var int */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id = null): void
    {
        $this->id = $id;
    }
}
