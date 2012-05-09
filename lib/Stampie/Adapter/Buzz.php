<?php

namespace Stampie\Adapter;

use Buzz\Browser;

/**
 * Adapter for Kriss Wallsmith's Buzz library
 *
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
class Buzz implements AdapterInterface
{
    /**
     * @var Browser $browser
     */
    protected $browser;

    /**
     * @param Browser $browser
     */
    public function __construct(Browser $browser)
    {
        $this->browser = $browser;
    }

    /**
     * @return Browser
     */
    public function getBrowser()
    {
        return $this->browser;
    }

    /**
     * @param string $endpoint
     * @param string $content
     * @param array $headers
     * @return Response
     */
    public function send($endpoint, $content, array $headers = array())
    {
        // Make headers buzz friendly
        array_walk($headers, function(&$value, $key) {
            $value = sprintf('%s: %s', $key, $value);
        });

        $response = $this->browser->post($endpoint, array_values($headers), $content);

        return new Response($response->getStatusCode(), $response->getContent());
    }
}
