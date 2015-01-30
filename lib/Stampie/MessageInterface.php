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
     * @return RecipientInterface|string
     */
    function getFrom();

    /**
     * @return RecipientInterface[]|string
     */
    function getTo();

    /**
     * @return RecipientInterface[]|string
     */
    function getCc();

    /**
     * @return RecipientInterface[]|$string
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
