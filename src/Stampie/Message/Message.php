<?php

/*
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Stampie\Message;

/**
 * @package Stampie
 */
class Message implements MessageInterface
{
    protected $html;
    protected $text;
    protected $from;
    protected $subject;

    /**
     * @param string   $subject
     * @param string   $html
     * @param string   $text
     * @param Identity $from
     */
    public function __construct($subject = null, $html = null, $text = null, Identity $from = null)
    {
        $this->subject = $subject;
        $this->html = $html;
        $this->text = $text;
        $this->from = $from;
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
}
