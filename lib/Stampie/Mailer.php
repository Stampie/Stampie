<?php

namespace Stampie;

use Stampie\Adapter\AdapterInterface;

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
     * {@inheritdoc}
     */
    public function setAdapter(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * {@inheritdoc}
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function getServerToken()
    {
        return $this->serverToken;
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function getHeaders()
    {
        return array();
    }
}
