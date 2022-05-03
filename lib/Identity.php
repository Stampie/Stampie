<?php

namespace Stampie;

/**
 * @author Christophe Coevoet <stof@notk.org>
 */
class Identity implements IdentityInterface
{
    /**
     * @var string|null
     */
    private $email;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @param string|null $email
     * @param string|null $name
     */
    public function __construct($email = null, $name = null)
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
