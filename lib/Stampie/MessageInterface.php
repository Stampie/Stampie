<?php

namespace Stampie;

/**
 * Represents a simple Message. A Message is a storage of a message that
 * will be converted into an API call
 *
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
interface MessageInterface
{
    /**
     * @return IdentityInterface|string
     */
    function getFrom();

    /**
     * @return IdentityInterface[]|string
     */
    function getTo();

    /**
     * @return IdentityInterface[]|string
     */
    function getCc();

    /**
     * @return IdentityInterface[]|$string
     */
    function getBcc();

    /**
     * @return string
     */
    function getSubject();

    /**
     * @return string
     */
    function getReplyTo();

    /**
     * @return array
     */
    function getHeaders();

    /**
     * @return string
     */
    function getHtml();

    /**
     * @return string
     */
    function getText();
}
