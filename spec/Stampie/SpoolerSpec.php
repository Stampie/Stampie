<?php

namespace spec\Stampie;

use Prophecy\Argument;

class SpoolerSpec extends \PhpSpec\ObjectBehavior
{
    /**
     * @param Stampie\Mailer $mailer
     * @param Stampie\Spool\Storage $storage
     */
    function let($mailer, $storage)
    {
        $this->beConstructedWith($mailer, $storage);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Stampie\Spooler');
    }

    /**
     * @param Stampie\Recipient $to
     * @param Stampie\Message $message
     */
    function it_stores_instead_of_sending($to, $message, $mailer, $storage)
    {
        $mailer->send($to, $message)->shouldNotBeCalled();

        $storage->push($to, $message)->shouldBeCalled();

        $this->send($to, $message);
    }

    /**
     * @param Stampie\Recipient $to
     * @param Stampie\Message $message
     */
    function it_delegates_stored_messages_to_mailer($to, $message, $mailer, $storage)
    {
        $mailer->send($to, $message)->shouldBeCalledTimes(2);

        $count = 0;

        // This is the biggest hack know to man  and is because of ReturnPromise is implemented
        // like "Returns saved values one by one until last one, then continuously returns last value."
        $storage->pop()->will(function () use ($to, $message, &$count) {
            return ($count += 1) < 3 ? [$to, $message] : null;
        });

        $this->flushSpool();
    }
}
