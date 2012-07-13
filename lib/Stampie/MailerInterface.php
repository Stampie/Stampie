<?php

namespace Stampie;

use Stampie\Adapter\AdapterInterface;
use Stampie\Adapter\ResponseInterface;

/**
 * Takes a MailerInterface and sends to to Postmark throgh Buzz
 *
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
interface MailerInterface
{
    /**
     * @param AdapterInterface $adapter
     */
    function setAdapter(AdapterInterface $adapter);

    /**
     * @return AdapterInterface
     */
    function getAdapter();

    /**
     * @param string $token
     */
    function setServerToken($token);

    /**
     * @return string
     */
    function getServerToken();

    /**
     * @param MessageInterface $message
     *
     * @return Boolean
     */
    function send(MessageInterface $message);
}
