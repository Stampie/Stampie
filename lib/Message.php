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
     * @var IdentityInterface|string
     */
    protected $to;

    /**
     * @var string|null
     */
    protected $html;

    /**
     * @var string|null
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

    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param string|null $html
     *
     * @return void
     */
    public function setHtml($html)
    {
        $this->html = $html;
    }

    /**
     * @param string $text
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    public function setText($text)
    {
        if ($text !== strip_tags($text)) {
            throw new \InvalidArgumentException('HTML Detected');
        }

        $this->text = $text;
    }

    public function getHtml()
    {
        return $this->html;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getHeaders()
    {
        return [];
    }

    public function getReplyTo()
    {
        $from = $this->getFrom();

        if ($from instanceof IdentityInterface) {
            $from = $from->getEmail();
        }

        return $from;
    }

    public function getCc()
    {
        return null;
    }

    public function getBcc()
    {
        return null;
    }
}
