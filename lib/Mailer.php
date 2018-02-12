<?php

namespace Stampie;

use Http\Client\HttpClient;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\MessageFactory;
use Http\Message\MultipartStream\MultipartStreamBuilder;
use Psr\Http\Message\ResponseInterface;
use Stampie\Util\IdentityUtils;

/**
 * Minimal implementation of a MailerInterface.
 *
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
abstract class Mailer implements MailerInterface
{
    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @var string
     */
    protected $serverToken;

    /**
     * @var MessageFactory
     */
    private $messageFactory;

    /**
     * @param HttpClient $httpClient
     * @param string     $serverToken
     */
    public function __construct(HttpClient $httpClient, $serverToken)
    {
        $this->setHttpClient($httpClient);
        $this->setServerToken($serverToken);
    }

    /**
     * @param HttpClient $httpClient
     */
    public function setHttpClient(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @return HttpClient
     */
    private function getHttpClient()
    {
        return $this->httpClient;
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
     *
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
        if (in_array($response->getStatusCode(), range(200, 206), true)) {
            return;
        }

        $this->handle($response);
    }

    /**
     * Return a key -> value array of headers.
     *
     * example:
     *     array('X-Header-Name' => 'value')
     *
     * @return array
     */
    protected function getHeaders()
    {
        return [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];
    }

    /**
     * Return an key -> value array of files.
     *
     * example:
     *     ['foo_files' => array('attachmentname.jpg' => '/path/to/file.jpg')]
     *
     * @param MessageInterface $message
     *
     * @return string[]
     */
    protected function getFiles(MessageInterface $message)
    {
        return [];
    }

    /**
     * @return string
     */
    abstract protected function getEndpoint();

    /**
     * Return a a string formatted for the correct Mailer endpoint.
     * Postmark this is Json, SendGrid it is a urlencoded parameter list.
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
     * Take a Message and return a PSR ResponseInterface.
     *
     * @param MessageInterface $message
     *
     * @return ResponseInterface
     */
    private function doSend(MessageInterface $message)
    {
        $content = $this->format($message);
        $headers = $this->getHeaders();
        $files = $this->getFiles($message);

        if (!empty($files)) {
            // HTTP query content
            parse_str($content, $fields);
            $builder = new MultipartStreamBuilder();

            foreach ($fields as $name => $value) {
                if (is_array($value)) {
                    foreach ($value as $c) {
                        $builder->addResource($name.'[]', $c);
                    }
                    continue;
                }

                $builder->addResource($name, $value);
            }

            // Add files to request
            foreach ($files as $key => $items) {
                foreach ($items as $name => $path) {
                    $options = [];
                    if (!is_numeric($name)) {
                        $options['filename'] = $name;
                    }
                    $value = fopen($path, 'r');
                    $builder->addResource($key, $value, $options);
                }
            }

            $content = $builder->build();
            $headers['Content-Type'] = 'multipart/form-data; boundary="'.$builder->getBoundary().'"';
        }

        $request = $this->getMessageFactory()->createRequest('POST', $this->getEndpoint(), $headers, $content);

        return $this->getHttpClient()->sendRequest($request);
    }
}
