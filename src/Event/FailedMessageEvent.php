<?php

/*
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Stampie\Event;

use Stampie\Message;
use Stampie\Recipient;

class FailedMessageEvent extends AbstractMessageEvent
{
    protected $exception;

    /**
     * @param Recipient  $to
     * @param Message    $message
     * @param \Exception $exception
     */
    public function __construct(Recipient $to, Message $message, \Exception $exception)
    {
        parent::__construct($to, $message);

        $this->exception = $exception;
    }

    /**
     * @return \Exception
     */
    public function getException()
    {
        return $this->exception;
    }
}
