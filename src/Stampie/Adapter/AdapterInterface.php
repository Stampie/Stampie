<?php

namespace Stampie\Adapter;

/**
 * @package Stampie
 */
interface AdapterInterface
{
    /**
     * @param string $endpoint
     * @param string $content
     * @param array $headers
     * @return string
     */
    public function request($endpoint, $content, array $headers = array());
}
