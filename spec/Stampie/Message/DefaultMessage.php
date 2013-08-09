<?php

namespace spec\Stampie\Message;

use PHPSpec2\ObjectBehavior;

class DefaultMessage extends ObjectBehavior
{
    /**
     * @param Stampie\Message\Identity $identity
     */
    function let($identity)
    {
        $this->beConstructedWith($identity);
    }

    function it_returns_constructor_arguments_through_getters($identity)
    {
        $this->beConstructedWith($identity, 'subject', 'html', 'text', array('value' => 'key'));

        $this->getSubject()->shouldReturn('subject');
        $this->getHtml()->shouldReturn('html');
        $this->getText()->shouldReturn('text');
        $this->getFrom()->shouldReturn($identity);
        $this->getHeaders()->shouldReturn(array('value' => 'key'));

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
        $this->getHeaders()->shouldReturn(array());

        $this->getSubject();
        $this->getHtml();
        $this->getText();
        $this->getHeaders();
    }
}
