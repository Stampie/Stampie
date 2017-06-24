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
    public function setHttpClient(HttpClient $adapter);

    /**
     * @param string $token
     */
    public function setServerToken($token);

    /**
     * @return string
     */
    public function getServerToken();

    /**
     * @param MessageInterface $message
     *
     * @return bool
     */
    public function send(MessageInterface $message);
}
