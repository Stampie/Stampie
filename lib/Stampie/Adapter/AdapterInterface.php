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
     * @return mixed
     */
    function send($endpoint, $content, array $headers = array());
}
