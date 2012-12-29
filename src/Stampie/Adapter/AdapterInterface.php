<?php

/*
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Stampie\Adapter;

/**
 * @package Stampie
 */
interface AdapterInterface
{
    /**
     * @param  Request $request
     * @return string
     */
    public function request(Request $request);
}
