<?php

/*
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Stampie\Carrier;

abstract class AbstractCarrier implements \Stampie\Carrier
{
    protected $key;

    /**
     * @param string $key
     */
    public function __construct($key)
    {
        $this->key = $key;
    }
}
