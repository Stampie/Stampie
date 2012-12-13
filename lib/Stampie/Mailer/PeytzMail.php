<?php

namespace Stampie\Mailer;

use Stampie\MessageInterface;
use Stampie\Message\TaggableInterface;
use Stampie\Adapter\ResponseInterface;
use Stampie\Exception\HttpException;

/**
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
class PeytzMail extends \Stampie\Mailer
{
    /**
     * {@inheritdoc}
     */
    protected function getEndpoint()
    {
        return strtr('https://<customer>.peytzmail.com/api/v1/trigger_mails.json', array(
            '<customer>' => current(explode(':', $this->getServerToken())),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function setServerToken($serverToken)
    {
        if (false == strpos($serverToken, ':')) {
            throw new \InvalidArgumentException('PeytzMail uses a "customer:key" based ServerToken.');
        }

        parent::setServerToken($serverToken);
    }

    /**
     * {@inheritdoc}
     */
    protected function getHeaders()
    {
        list(, $serverToken) = explode(':', $this->getServerToken());

        return array(
            'Accept' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode($serverToken . ':'),
            'Content-Type' => 'application/json',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function send(MessageInterface $message)
    {
        if (false == $message instanceof TaggableInterface) {
            throw new \InvalidArgumentException('PeytzMail can only send messages which implement "TaggableInterface".');
        }

        parent::send($message);
    }

    /**
     * {@inheritdoc}
     */
    protected function format(MessageInterface $message)
{
        $tag = $message->getTag();
        if (is_array($tag)) {
            $tag = reset($tag);
        }

        $parameters = array(
            'email' => current($this->normalizeIdentities($message->getTo()))->getEmail(),
            'subject' => $message->getSubject(),
            'from_email' => $this->normalizeIdentity($message->getFrom())->getEmail(),
            'tag' => $tag,
            'content' => array(
                'html' => $message->getHtml(),
                'text' => $message->getText(),
            ),
        );

        return json_encode(array(
            'trigger_mail' => $parameters,
        ));
    }

    /**
     * {@inheritdoc}
     */
    protected function handle(ResponseInterface $response)
    {
        throw new HttpException($response->getStatusCode(), $response->getStatusText());
    }
}
