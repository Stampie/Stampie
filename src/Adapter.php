<?php

/*
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Stampie;

/**
 * @package Stampie
 */
interface Adapter
{
    /**
     * @param  Request $request
     * @return string
     */
    public function request(Request $request);
}
