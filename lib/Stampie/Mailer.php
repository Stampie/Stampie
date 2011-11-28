<?php

namespace Stampie;

use Buzz\Browser;

/**
 * Sends emails to Postmark server
 *
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
class Mailer implements MailerInterface
{
    /**
     * Endpoint for the api call.
     */
    const ENDPOINT = 'http://api.postmarkapp.com/email';

    /**
     * @var Browser $browser
     */
    protected $browser;

    /**
     * @var string
     */
    protected $serverToken;

    /**
     * @param Browser $browser
     * @param string $serverToken
     */
    public function __construct(Browser $browser, $serverToken = "POSTMARK_API_TEST")
    {
        $this->setBrowser($browser);
        $this->setServerToken($serverToken);
    }

    /**
     * @param Browser $browser
     */
    public function setBrowser(Browser $browser)
    {
        $this->browser = $browser;
    }

    /**
     * @return Browser
     */
    public function getBrowser()
    {
        return $this->browser;
    }

    /**
     * @param string $serverToken
     * @throws \InvalidArgumentException
     */
    public function setServerToken($serverToken)
    {
        if (empty($serverToken)) {
            throw new \InvalidArgumentException('The provided server token cannot be empty');
        }

        $this->serverToken = $serverToken;
    }

    /**
     * @return string
     */
    public function getServerToken()
    {
        return $this->serverToken;
    }

    /**
     * @param MessageInterface $message
     * @throws \LogicException
     * @return Boolean
     */
    public function send(MessageInterface $message)
    {
        $headers = array(
            'X-Postmark-Server-Token' => $this->getServerToken(),
            'Content-Type' => 'application/json',
        );

        if (!is_string($content = json_encode($this->prepare($message)))) {
            throw new \LogicException('Could not create json contents. php.net/json_last_error code:' . json_last_error());
        }

        $response = $this->browser->post(static::ENDPOINT, $headers, $content);

        // We are all clear if status is HTTP 200 OK
        if ($response->getStatusCode() === 200) {
            return true;
        }

        // 401 Unauthorized 
        // 500 Internal Server Error
        if (in_array($statusCode = $response->getStatusCode(), array(401, 500))) {
            throw new \LogicException($statusCode == 401 ? 'Unauthorized' : 'Internal Server Error', $statusCode);
        }

        // The error is returned in JSON with {ErrorCode : 405, Message: "Details"}
        $error = json_decode($response->getContent());

        // 422 Unprocessable Entity
        throw new \LogicException($error->Message, $error->ErrorCode);
    }

    /**
     * @param MessageInterface $message
     * @throws \InvalidArgumentException
     * @return array
     */
    protected function prepare(MessageInterface $message)
    {
        $parameters = array_filter(array(
            'From'     => $message->getFrom(),
            'To'       => $message->getTo(),
            'Subject'  => $message->getSubject(),
            'Headers'  => $message->getHeaders(),
            'TextBody' => $message->getText(),
            'HtmlBody' => $message->getHtml(),
        ));

        if (empty($parameters['HtmlBody']) && empty($parameters['TextBody'])) {
            throw new \InvalidArgumentException('You cannot send empty emails');
        }

        return $parameters;
    }
}
