<?php

namespace spec\Stampie;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AttachmentSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Stampie\Attachment');
    }

    function let()
    {
        $this->beConstructedWith(__DIR__ . '/../Fixtures/attachment.json');
    }

    function it_guesses_content_type()
    {
        $this->getContentType()->shouldReturn('text/plain');
    }

    function it_gets_content()
    {
        $this->getContent()->shouldReturn("{ \"hello\" : \"sir\" }\n");
    }

    function it_uses_basename_if_none_given()
    {
        $this->getName()->shouldReturn('attachment.json');
    }

    function it_allows_custom_name()
    {
        $this->beConstructedWith(__DIR__ . '/Fixtures/attachment.json', 'customname.jpg');

        $this->getName()->shouldReturn('customname.jpg');
    }
}
