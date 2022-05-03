<?php

namespace Stampie;

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Message\MessageFactory;
use Http\Message\MultipartStream\MultipartStreamBuilder;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Stampie\Util\IdentityUtils;

/**
 * Minimal implementation of a MailerInterface.
 *
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
abstract class Mailer implements MailerInterface
{
    /**
     * @var ClientInterface
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
     * @var RequestFactoryInterface|null
     */
    private $requestFactory;

    /**
     * @var StreamFactoryInterface|null
     */
    private $streamFactory;

    public function __construct(ClientInterface $httpClient, string $serverToken)
    {
        $this->setHttpClient($httpClient);
        $this->setServerToken($serverToken);
    }

    public function setHttpClient(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function setRequestFactory(?RequestFactoryInterface $requestFactory): void
    {
        $this->requestFactory = $requestFactory;
    }

    /**
     * @return RequestFactoryInterface|MessageFactory
     */
    private function getRequestFactory()
    {
        return $this->requestFactory ?? $this->messageFactory ?? ($this->httpClient instanceof RequestFactoryInterface ? $this->httpClient : Psr17FactoryDiscovery::findRequestFactory());
    }

    public function setStreamFactory(?StreamFactoryInterface $streamFactory): void
    {
        $this->streamFactory = $streamFactory;
    }

    private function getStreamFactory(): StreamFactoryInterface
    {
        return $this->streamFactory ?? ($this->httpClient instanceof StreamFactoryInterface ? $this->httpClient : Psr17FactoryDiscovery::findStreamFactory());
    }

    /**
     * @param MessageFactory $messageFactory
     *
     * @return Mailer
     *
     * @deprecated use "setRequestFactory" instead or provide a PSR-18 client implementing RequestFactoryInterface directly
     */
    public function setMessageFactory(MessageFactory $messageFactory)
    {
        trigger_deprecation('stampie/stampie', '1.2.0', sprintf('The "%s" method is deprecated. Use "setRequestFactory" instead or provide a PSR-18 client implementing RequestFactoryInterface directly.', __METHOD__));

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
     * Return a key -> value array of files.
     *
     * This implies that the formatted payload uses application/x-www-form-urlencoded.
     *
     * example:
     *     ['foo_files' => array('attachmentname.jpg' => '/path/to/file.jpg')]
     *
     * @param MessageInterface $message
     *
     * @return array<string, array<int|string, string>
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
     * Returns a string formatted for the correct Mailer endpoint.
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

        if (empty($files)) {
            $content = $this->getStreamFactory()->createStream($content);
        } else {
            // HTTP query content
            parse_str($content, $fields);
            $builder = new MultipartStreamBuilder($this->streamFactory);

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

        $request = $this->getRequestFactory()->createRequest('POST', $this->getEndpoint())
            ->withBody($content);

        foreach ($headers as $headerName => $headerValue) {
            $request = $request->withHeader($headerName, $headerValue);
        }

        return $this->httpClient->sendRequest($request);
    }
}
