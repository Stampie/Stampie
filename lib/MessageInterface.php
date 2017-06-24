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
     * @return IdentityInterface|string
     */
    public function getFrom();

    /**
     * @return IdentityInterface[]|string
     */
    public function getTo();

    /**
     * @return IdentityInterface[]|string
     */
    public function getCc();

    /**
     * @return IdentityInterface[]|$string
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
