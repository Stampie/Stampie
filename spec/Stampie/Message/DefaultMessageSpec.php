<?php

namespace spec\Stampie\Message;

class DefaultMessageSpec extends \PhpSpec\ObjectBehavior
{
    /**
     * @param Stampie\Identity $identity
     */
    function let($identity)
    {
        $this->beConstructedWith($identity);
    }

    function it_returns_constructor_arguments_through_getters($identity)
    {
        $this->beConstructedWith($identity, 'subject', 'html', 'text', ['value' => 'key']);

        $this->getSubject()->shouldReturn('subject');
        $this->getHtml()->shouldReturn('html');
        $this->getText()->shouldReturn('text');
        $this->getFrom()->shouldReturn($identity);
        $this->getHeaders()->shouldReturn(['value' => 'key']);

        $this->getSubject();
        $this->getHtml();
        $this->getText();
        $this->getFrom();
        $this->getHeaders();
    }

    function it_have_optional_constructor_arguments_except_from()
    {
        $this->getSubject()->shouldReturn(null);
        $this->getHtml()->shouldReturn(null);
        $this->getText()->shouldReturn(null);
        $this->getHeaders()->shouldReturn([]);

        $this->getSubject();
        $this->getHtml();
        $this->getText();
        $this->getHeaders();
    }
}
