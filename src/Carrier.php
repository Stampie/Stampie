<?php

/*
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Stampie;

interface Carrier
{
    /**
     * @param Recipient $to
     * @param Message   $message
     *
     * @return Request
     */
    public function createRequest(Recipient $to, Message $message);

    /**
     * @param Response $response
     *
     * @throws \LogicException|\RuntimeException|UnauthorizedException
     *
     * @return string|integer|null
     */
    public function handleResponse(Response $response);
}
