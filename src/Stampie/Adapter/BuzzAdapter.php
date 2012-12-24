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
