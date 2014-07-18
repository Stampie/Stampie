<?php

namespace spec\Stampie\Handler;

use Prophecy\Argument;

class PostmarkHandlerSpec extends \PhpSpec\ObjectBehavior
{
    /**
     * @param Stampie\Adapter $adapter
     * @param Stampie\Message $message
     * @param Stampie\Identity $identity
     */
    function let($adapter, $message, $identity)
    {
        $this->beConstructedWith($adapter, 'my-personal-key');
    }

    /**
     * @param Stampie\Adapter\Response $response
     */
    function it_calls_the_adapter_with_a_request($response, $adapter, $message, $identity)
    {
        $this->prepareMessageMock($message, $identity);

        $identity->__toString()->willReturn('henrik@bjrnskov.dk');

        $response->isSuccessful()->willReturn(true);
        $response->getContent()->willReturn('{"MessageID" : "This is the MessageID" }');

        $adapter->request(Argument::type('Stampie\Adapter\Request'))->shouldBeCalled()->willReturn($response);

        $this->send($identity, $message);
    }

    /**
     * @param Stampie\Adapter\Response $response
     */
    function it_throws_an_exception_when_response_is_unauthorized($response, $adapter, $message, $identity)
    {
        $this->prepareMessageMock($message, $identity);

        $identity->__toString()->willReturn('henrik@bjrnskov.dk');

        $adapter->request(Argument::any())->willReturn($response);

        $response->isSuccessful()->willReturn(false);
        $response->isUnauthorized()->willReturn(true);

        $this->shouldThrow('Stampie\Exception\UnauthorizedException')->duringSend($identity, $message);
    }

    private function prepareMessageMock($message, $identity)
    {
        $message->getFrom()->willReturn($identity);
        $message->getHeaders()->willReturn(array());
        $message->getSubject()->willReturn('Subject');
        $message->getHtml()->willReturn('<b>Html</b>');
        $message->getText()->willReturn('Text');
    }
}
