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
    function getTag();

    /**
     * @return string
     */
    function getReplyTo();

    /**
     * @return array
     */
    function getHeaders();

    /**
     * @param array $headers
     */
    function setHeaders(array $headers);

    /**
     * @param string $name
     * @param string $value
     */
    function addHeader($name, $value);

    /**
     * @param string $name
     */
    function getHeader($name);
}
