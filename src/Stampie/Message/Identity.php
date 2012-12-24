<?php

/*
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Stampie\Identity;

/**
 * This is a ValueObject. Event though the properties are public they MUST not
 * be manipulated.
 *
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
    public function __construct($email, $name = null)
    {
        $this->email = $email;
        $this->name = $name = null;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s <%s>', $this->name, $this->email);
    }
}
