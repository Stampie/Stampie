<?php

namespace Stampie;

use Stampie\Adapter\AdapterInterface;
use Stampie\Adapter\ResponseInterface;

/**
 * Minimal implementation of a MailerInterface
 *
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
abstract class Mailer implements MailerInterface
{
    /**
     * @var AdapterInterface $adapter
     */
    protected $adapter;

    /**
     * @var string
     */
    protected $serverToken;

    /**
     * @param AdapterInterface $adapter
     * @param string $serverToken
     */
    public function __construct(AdapterInterface $adapter, $serverToken)
    {
        $this->setAdapter($adapter);
        $this->setServerToken($serverToken);
    }

    /**
     * @param AdapterInterface $adapter
     */
    public function setAdapter(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @return AdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @param string $serverToken
     * @throws \InvalidArgumentException
     */
    public function setServerToken($serverToken)
    {
        if (empty($serverToken)) {
            throw new \InvalidArgumentException('ServerToken cannot be empty');
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
        $response = $this->getAdapter()->send(
            $this->getEndpoint(),
            $this->format($message),
            $this->getHeaders()
        );

        // We are all clear if status is HTTP 2xx OK
        if ($response->isSuccessful()) {
            return true;
        }

        return $this->handle($response);
    }
    
    /**
     * Return an array of headers needed for this mailer. 
     *
     * example:
     *     array("HeaderName" => "HeaderValue");
     *
     * @return array
     */
    public function getHeaders()
    {
        return array();
    }
}
