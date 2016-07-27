<?php

namespace Stampie;

use Http\Client\HttpClient;

/**
 * Takes a MailerInterface and sends to an AdapterInterface.
 *
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
interface MailerInterface
{
    /**
     * @param HttpClient $adapter
     */
    function setHttpClient(HttpClient $adapter);

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
