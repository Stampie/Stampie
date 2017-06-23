<?php

namespace Stampie\Adapter;

/**
 * Interface for returned content by adapters.
 *
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
interface ResponseInterface
{
    /**
     * @return int
     */
    public function getStatusCode();

    /**
     * @return string
     */
    public function getContent();

    /**
     * @return string
     */
    public function getStatusText();

    /**
     * @return bool
     */
    public function isSuccessful();
}
