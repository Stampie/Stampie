<?php

namespace Stampie;

/**
 * Represents a simple Message. A Message is a storage og a message that
 * will be converted into an API call
 *
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
interface MessageInterface
{
    /**
     * @return string
     */
    function getFrom();

    /**
     * @return string
     */
    function getTo();

    /**
     * @return string
     */
    function getCc();

    /**
     * @return $string
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
