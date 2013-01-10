<?php

namespace Stampie;

/**
 * Implementation of MessageInterface where only getFrom() and getSubject()
 * is required to be implemented.
 *
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
abstract class Message implements MessageInterface
{
    /**
     * @var string
     */
    protected $to;

    /**
     * @var string
     */
    protected $html;

    /**
     * @var string
     */
    protected $text;

    /**
     * @param IdentityInterface|string $to
     */
    public function __construct($to)
    {
        $email = $to instanceof IdentityInterface ? $to->getEmail() : $to;
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email');
        }

        $this->to = $to;
    }

    /**
     * @return string
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param string $html
     */
    public function setHtml($html)
    {
        $this->html = $html;
    }

    /**
     * @param string $text
     * @throws \InvalidArgumentException
     */
    public function setText($text)
    {
        if ($text !== strip_tags($text)) {
            throw new \InvalidArgumentException('HTML Detected');
        }

        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return array();
    }

    /**
     * @return string
     */
    public function getReplyTo()
    {
        $from = $this->getFrom();

        if ($from instanceof IdentityInterface) {
            $from = $from->getEmail();
        }

        return $from;
    }

    /**
     * @return null
     */
    public function getCc()
    {
        return null;
    }

    /**
     * @return null
     */
    public function getBcc()
    {
        return null;
    }
}
