<?php

/*
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Stampie\Adapter;

use Buzz\Browser;

/**
 * @package Stampie
 */
class BuzzAdapter implements \Stampie\Adapter
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
    public function request(Request $request)
    {
        $headers = $request->getHeaders();

        array_walk($headers, function (&$value, $key) {
            $value = sprintf('%s: %s', $key, $value);
        });

        $response = $this->browser->post($request->getUrl(), array_values($headers), $request->getContent());

        return new Response($response->getStatusCode(), $response->getContent(), $response->getHeaders());
    }
}
