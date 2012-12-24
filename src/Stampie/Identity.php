<?php

namespace Stampie;

/**
 * @author Christophe Coevoet <stof@notk.org>
 * @package Stampie
 */
class Identity
{
    public $name;
    public $email;

    /**
     * @param string $email
     * @param string $name
     */
    public function __construct($email, $name)
    {
        $this->email = $email;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s <%s>', $this->name, $this->email);
    }
}
