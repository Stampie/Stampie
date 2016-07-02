<?php

namespace Stampie;

use GuzzleHttp\Psr7\MultipartStream;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Discovery\StreamFactoryDiscovery;
use Http\Message\MessageFactory;
use Stampie\Adapter\Response;
use Stampie\Adapter\ResponseInterface;
use Stampie\Util\IdentityUtils;

/**
 * Minimal implementation of a MailerInterface
 *
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
abstract class Mailer implements MailerInterface
{
    /**
     * @var HttpClient $adapter
     */
    protected $adapter;

    /**
     * @var string
     */
    protected $serverToken;

    /**
     * @var MessageFactory
     */
    private $messageFactory;

    /**
     * @param HttpClient $adapter
     * @param string     $serverToken
     */
    public function __construct(HttpClient $adapter = null, $serverToken)
    {
        $this->setAdapter($adapter ?: HttpClientDiscovery::find());
        $this->setServerToken($serverToken);
    }

    /**
     * {@inheritdoc}
     */
    public function setAdapter(HttpClient $adapter)
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
     * @return MessageFactory
     */
    private function getMessageFactory()
    {
        return $this->messageFactory ?: MessageFactoryDiscovery::find();
    }

    /**
     * @param MessageFactory $messageFactory
     *
     * @return Mailer
     */
    public function setMessageFactory(MessageFactory $messageFactory)
    {
        $this->messageFactory = $messageFactory;

        return $this;
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
        $response = $this->doSend($message);

        // We are all clear if status is HTTP 2xx OK
        if ($response->isSuccessful()) {
            return true;
        }

        return $this->handle($response);
    }

    /**
     * Return a key -> value array of headers
     *
     * example:
     *     array('X-Header-Name' => 'value')
     *
     * @return array
     */
    protected function getHeaders()
    {
        return array(
            'Content-Type' => 'application/x-www-form-urlencoded',
            );
    }

    /**
     * Return an key -> value array of files
     *
     * example:
     *     array('attachmentname.jpg' => '/path/to/file.jpg')
     *
     * @param MessageInterface $message
     * @return string[]
     */
    protected function getFiles(MessageInterface $message)
    {
        return array();
    }

    /**
     * @return string
     */
    abstract protected function getEndpoint();

    /**
     * Return a a string formatted for the correct Mailer endpoint.
     * Postmark this is Json, SendGrid it is a urlencoded parameter list
     *
     * @param MessageInterface $message
     *
     * @return string
     */
    abstract protected function format(MessageInterface $message);

    /**
     * If a Response is not successful it will be passed to this method
     * each Mailer should then throw an HttpException with an optional
     * ApiException to help identify the problem.
     *
     * @param ResponseInterface $response
     *
     * @throws \Stampie\Exception\ApiException
     * @throws \Stampie\Exception\HttpException
     */
    abstract protected function handle(ResponseInterface $response);

    /**
     * @param IdentityInterface|string $identity
     *
     * @return IdentityInterface
     */
    protected function normalizeIdentity($identity)
    {
        return IdentityUtils::normalizeIdentity($identity);
    }

    /**
     * @param IdentityInterface[]|string $identities
     *
     * @return IdentityInterface[]
     */
    protected function normalizeIdentities($identities)
    {
        return IdentityUtils::normalizeIdentities($identities);
    }

    /**
     * @param IdentityInterface[]|IdentityInterface|string $identities
     *
     * @return string
     */
    protected function buildIdentityString($identities)
    {
        return IdentityUtils::buildIdentityString($identities);
    }

    /**
     * Take a Message and return a Stampie Response
     *
     * @param MessageInterface $message
     *
     * @return Response
     */
    private function doSend(MessageInterface $message)
    {
        $content = $this->format($message);
        $headers = $this->getHeaders();
        $files = $this->getFiles($message);

        if (!empty($files)) {
            // HTTP query content
            parse_str($content, $fields);
            $data = [];

            foreach ($fields as $name => $contents) {
                if (!is_array($contents)) {
                    $data[] = ['name'=>$name, 'contents'=>$contents];
                } else {
                    foreach ($contents as $c) {
                        $data[] = ['name'=>$name.'[]', 'contents'=>$c];
                    }
                }
            }

            // Add files to request
            foreach ($files as $key => $items) {
                foreach ($items as $name => $path) {
                    $d = ['name' => $key, 'contents' => fopen($path, 'r')];
                    if (!is_numeric($name)) {
                        $d['filename'] = $name;
                    }
                    $data[] = $d;
                }
            }

            $content = new MultipartStream($data);
            $headers['Content-Type'] = 'multipart/form-data; boundary='.$content->getBoundary();
        }

        $request = $this->getMessageFactory()->createRequest('POST', $this->getEndpoint(), $headers, $content);
        $psr7Response = $this->getAdapter()->sendRequest($request);

        return new Response($psr7Response->getStatusCode(), $psr7Response->getBody()->__toString());
    }
}
