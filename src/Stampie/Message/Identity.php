<?php

/*
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Stampie\Message;

/**
 * This is a ValueObject. __get makes it possible to use this kind a like
 * a ruby object with attr_reader.
 *
 * @author Christophe Coevoet <stof@notk.org>
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 * @package Stampie
 */
class Identity
{
    protected $name;
    protected $email;

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

    /**
     * @param string $property
     * @return mixed
     */
    public function __get($property)
    {
        return $this->$property;
    }
}
