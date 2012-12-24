<?php

namespace Stampie\Adapter;

use Buzz\Browser;

/**
 * @package Stampie
 */
class BuzzAdapter implements AdapterInterface
{
    protected $browser;

    /**
     * @param Browser $browser
     */
    public function __construct(Browser $browser)
    {
        $this->browser = $browser;
    }

    /**
     * {@inheritDoc}
     */
    public function request($endpoint, $content, array $headers = array())
    {
        array_walk($headers, function (&$value, $key) {
            $value = sprintf('%s: %s', $key, $value);
        });

        $request = $this->browser->post($endpoint, array_values($headers), $content);

        var_dump((string) $request);
    }
}
