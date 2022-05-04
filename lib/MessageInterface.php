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
     * @return IdentityInterface[]|IdentityInterface|string
     */
    public function getTo();

    /**
     * @return IdentityInterface[]|IdentityInterface|string|null
     */
    public function getCc();

    /**
     * @return IdentityInterface[]|IdentityInterface|string|null
     */
    public function getBcc();

    /**
     * @return string
     */
    public function getSubject();

    /**
     * @return string|null
     */
    public function getReplyTo();

    /**
     * @return array<string, string>
     */
    public function getHeaders();

    /**
     * @return string|null
     */
    public function getHtml();

    /**
     * @return string|null
     */
    public function getText();
}
