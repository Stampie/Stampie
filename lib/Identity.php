<?php

namespace Stampie;

/**
 * @author Christophe Coevoet <stof@notk.org>
 */
class Identity implements IdentityInterface
{
    private $email;
    private $name;

    public function __construct($email = null, $name = null)
    {
        $this->email = $email;
        $this->name = $name;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

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
