<?php

namespace spec\Stampie\Message;

use PHPSpec2\ObjectBehavior;

class Message extends ObjectBehavior
{
    /**
     * @param Stampie\Message\Identity $identity
     */
    function it_returns_constructor_arguments_through_getters($identity)
    {
        $this->beConstructedWith('subject', 'html', 'text', $identity);

        $this->getSubject()->shouldReturn('subject');
        $this->getHtml()->shouldReturn('html');
        $this->getText()->shouldReturn('text');
        $this->getFrom()->shouldReturn($identity);

        $this->getSubject();
        $this->getHtml();
        $this->getText();
        $this->getFrom();
    }

    function it_only_haves_optional_constructor_arguments()
    {
        $this->beConstructedWith();

        $this->getSubject()->shouldReturn(null);
        $this->getHtml()->shouldReturn(null);
        $this->getText()->shouldReturn(null);
        $this->getFrom()->shouldReturn(null);

        $this->getSubject();
        $this->getHtml();
        $this->getText();
        $this->getFrom();
    }
}
