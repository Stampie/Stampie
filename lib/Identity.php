<?php

namespace Stampie;

/**
 * @author Christophe Coevoet <stof@notk.org>
 */
class Identity implements IdentityInterface
{
    /**
     * @var string
     */
    private $email;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @param string      $email
     * @param string|null $name
     */
    public function __construct(string $email, $name = null)
    {
        $this->email = $email;
        $this->name = $name;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string|null $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }
}
