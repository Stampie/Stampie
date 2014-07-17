<?php

namespace Stampie\Adapter;

/**
 * Interface all adapters must implement.
 *
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
interface AdapterInterface
{
    /**
     * @param string $endpoint
     * @param string $content
     * @param array $headers
     * @param array $files
     * @return mixed
     */
    function send($endpoint, $content, array $headers = array(), array $files = array());
}
