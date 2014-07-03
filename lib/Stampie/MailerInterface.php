<?php

namespace Stampie;

use Stampie\Adapter\AdapterInterface;

/**
 * Takes a MailerInterface and sends to an AdapterInterface.
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
