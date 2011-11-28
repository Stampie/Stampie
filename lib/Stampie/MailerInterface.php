<?php

namespace Stampie;

use Buzz\Browser;

/**
 * Takes a MailerInterface and sends to to Postmark throgh Buzz
 *
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
interface MailerInterface
{
    /**
     * @param Browser $browser
     */
    function setBrowser(Browser $browser);

    /**
     * @return Browser
     */
    function getBrowser();

    /**
     * @param string $token
     */
    function setServerToken($token);

    /**
     * @return string
     */
    function getServerToken();

    /**
     * @param MailerInterface $message
     * @return Boolean
     */
    function send(MessageInterface $message);
}
