<?php

/*
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Stampie;

/**
 * This is a ValueObject. __get makes it possible to use this kind a like
 * a ruby object with attr_reader.
 *
 * @author Christophe Coevoet <stof@notk.org>
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
class Recipient
{
    private $name;
    private $email;

    /**
     * @param string $email
     * @param string $name
     */
    public function __construct($email, $name = null)
    {
        $this->email = $email;
        $this->name = $name;
    }

    /**
     * Formats as a name-addr if name is provided otherwise as
     * a addr-spec. According to RFC #2822 rules.
     *
     * @return string
     * @link http://tools.ietf.org/html/rfc2822#section-3.6.3
     */
    public function formatAsAddress()
    {
        return $this->name ? sprintf('%s <%s>', $this->name, $this->email) : $this->email;
    }

    /**
     * @param string $property
     *
     * @return mixed
     */
    public function __get($property)
    {
        return $this->$property;
    }

    /**
     * @param string $property
     *
     * @return boolean
     */
    public function __isset($property)
    {
        return isset($this->$property);
    }
}
