<?php

/*
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Stampie\Handler;

use Stampie\Adapter;
use Stampie\Adapter\Request;
use Stampie\Adapter\Response;
use Stampie\Message;
use Stampie\Message\MessageHeader;
use Stampie\Identity;

/**
 * @package Stampie
 */
abstract class AbstractHandler implements \Stampie\Handler
{
    protected $key;
    protected $adapter;

    /**
     * @param AdapterInterface $adapter
     * @param string           $key
     */
    public function __construct(Adapter $adapter, $key)
    {
        $this->adapter = $adapter;
        $this->key = $key;
    }

    /**
     * {@inheritDoc}
     */
    public function send(Identity $to, Message $message)
    {
        $request = new Request($this->endpoint, 'POST');
        $request->setContent($this->format($to, $message));

        $this->prepare($request);

        return new MessageHeader($this->handleResponse($this->adapter->request($request)));
    }

    /**
     * @param Response $response
     * @return string|integer|null
     */
    abstract protected function handleResponse(Response $response);

    /**
     * Used to format a message and identity into a string representation.
     * Normally this will be json or a query string.
     *
     * @param Identity     $to
     * @param Message      $message
     * @param Attachment[] $attachments
     */
    abstract protected function format(Identity $to, Message $message, $attachments = []);

    /**
     * Used to set additional headers or if the API key is
     * required in the request.
     *
     * @param Request $request
     */
    abstract protected function prepare(Request $request);
}
