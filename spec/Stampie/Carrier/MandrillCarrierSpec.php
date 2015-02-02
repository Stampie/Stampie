<?php

namespace spec\Stampie\Carrier;

use Prophecy\Argument;
use Stampie\Message\MessageHeader;

class MandrillCarrierSpec extends \PhpSpec\ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('my-personal-key');
    }

    /**
     * @param Stampie\Message $message
     * @param Stampie\Recipient $recipient
     */
    function it_creates_a_request_with_formatted_message($message, $recipient)
    {
        $recipient->formatAsAddress()->willReturn('henrik@bjrnskov.dk');

        $this->createRequest($recipient, $message)->shouldBeAnInstanceOf('Stampie\Request');
    }
}
