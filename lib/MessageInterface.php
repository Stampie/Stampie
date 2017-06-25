<?php

namespace Stampie;

/**
 * Represents a simple Message. A Message is a storage of a message that
 * will be converted into an API call.
 *
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
interface MessageInterface
{
    /**
     * @return RecipientInterface|string
     */
    public function getFrom();

    /**
     * @return RecipientInterface[]|string
     */
    public function getTo();

    /**
     * @return RecipientInterface[]|string
     */
    public function getCc();

    /**
     * @return RecipientInterface[]|$string
     */
    public function getBcc();

    /**
     * @return string
     */
    public function getSubject();

    /**
     * @return string
     */
    public function getReplyTo();

    /**
     * @return array
     */
    public function getHeaders();

    /**
     * @return string
     */
    public function getHtml();

    /**
     * @return string
     */
    public function getText();
}
