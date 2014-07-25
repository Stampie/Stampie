<?php

/*
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Stampie;

use Prophecy\Argument;
use Stampie\StampieEvents;
use Stampie\Message\MessageHeader;

class DirectMailerSpec extends \PhpSpec\ObjectBehavior
{
    /**
     * @param Stampie\Carrier $carrier
     * @param Stampie\Adapter $adapter
     */
    function let($carrier, $adapter)
    {
        $this->beConstructedWith($carrier, $adapter);
    }

    /**
     * @param Stampie\Identity $identity
     * @param Stampie\Message $message
     * @param Stampie\Request $request
     * @param Stampie\Response $response
     */
    function it_sends_message($identity, $message, $request, $response, $carrier, $adapter)
    {
        $carrier->createRequest($identity, $message)->shouldBeCalled()->willReturn($request);
        $carrier->handleResponse($response)->willReturn('my-message-id');

        $adapter->request($request)->shouldBeCalled()->willReturn($response);

        $this->send($identity, $message)->shouldBeLike(new MessageHeader('my-message-id'));
    }

    /**
     * @param Stampie\Identity $identity
     * @param Stampie\Message $message
     * @param Stampie\Request $request
     * @param Stampie\Response $response
     */
    function it_raises_exception($identity, $message, $request, $response, $carrier, $adapter)
    {
        $adapter->request($request)->willReturn($response);

        $carrier->createRequest($identity, $message)->willReturn($request);
        $carrier->handleResponse($response)->willThrow('RuntimeException');

        $this->shouldThrow('RuntimeException')->duringSend($identity, $message);
    }
}
