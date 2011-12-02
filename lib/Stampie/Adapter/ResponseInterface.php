<?php

namespace Stampie\Adapter;

/**
 * Interface for returned content by adapters
 *
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
interface ResponseInterface
{
    /**
     * @return integer
     */
    function getStatusCode();

    /**
     * @return string
     */
    function getContent();

    /**
     * @return string
     */
    function getStatusText();

    /**
     * @return Boolean
     */
    function isSuccessful();
}
