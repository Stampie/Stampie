<?php

namespace Stampie;

use Stampie\Adapter\AdapterInterface;

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
     * @return string
     */
    function getEndpoint();

    /**
     * Return a key -> value array of headers
     *
     * example:
     *     array('X-Header-Name' => 'value')
     *
     * @return array
     */
    function getHeaders();

    /**
     * Return a a string formatted for the correct Mailer endpoint.
     * Postmark this is Json, SendGrid it is a urlencoded parameter list
     *
     * @return string
     */
    function format(MessageInterface $message);

    /**
     * If a Response is not successful it will be passed to this method
     * each Mailer should then throw an HttpException with an optional
     * ApiException to help identify the problem.
     *
     * @throws ApiException
     * @throws HttpException
     */
    function handle(ResponseInterface $response);

    /**
     * @param MailerInterface $message
     * @return Boolean
     */
    function send(MessageInterface $message);
}
