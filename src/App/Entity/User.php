<?php

namespace App\Entity;

use Persistence\EntityInterface;

/**
 * Class User
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class User implements EntityInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $plainPassword;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \DateTime
     */
    private $birthday;

    /**
     * @var bool
     */
    private $active = false;


    /**
     * @inheritDoc
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function setId(int $id = null): void
    {
        $this->id = $id;
    }

    /**
     * Returns the email.
     *
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Sets the email.
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Returns the password.
     *
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Sets the password.
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword(string $password = null): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returns the plain password.
     *
     * @return string
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * Sets the plain password.
     *
     * @param string $plain
     *
     * @return User
     */
    public function setPlainPassword(string $plain = null): self
    {
        $this->plainPassword = $plain;

        return $this;
    }

    /**
     * Returns the name.
     *
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Sets the name.
     *
     * @param string $name
     *
     * @return User
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Returns the birthday.
     *
     * @return \DateTime
     */
    public function getBirthday(): ?\DateTime
    {
        return $this->birthday;
    }

    /**
     * Sets the birthday.
     *
     * @param \DateTime $birthday
     *
     * @return User
     */
    public function setBirthday(\DateTime $birthday = null): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Returns whether the user is active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * Sets whether the user is active.
     *
     * @param bool $active
     *
     * @return User
     */
    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}
