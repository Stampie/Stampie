<?php

/*
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Stampie\Message;

use Stampie\Identity;

/**
 * @package Stampie
 */
class DefaultMessage implements \Stampie\Message
{
    protected $from;
    protected $html;
    protected $text;
    protected $subject;
    protected $headers;

    /**
     * @param Identity $from
     * @param string   $subject
     * @param string   $html
     * @param string   $text
     */
    public function __construct(Identity $from, $subject = null, $html = null, $text = null, array $headers = array())
    {
        $this->from = $from;
        $this->subject = $subject;
        $this->html = $html;
        $this->text = $text;
        $this->headers = $headers;
    }

    /**
     * {@inheritDoc}
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * {@inheritDoc}
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * {@inheritDoc}
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * {@inheritDoc}
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * {@inhertiDoc}
     */
    public function getHeaders()
    {
        return $this->headers;
    }
}
