<?php

namespace Stampie\Adapter;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Post\PostBody;
use GuzzleHttp\Post\PostFile;
use GuzzleHttp\Stream\Stream;

/**
 * GuzzleHttp Adapter (guzzlephp.org) - Version 5+
 *
 * @author Gauthier Wallet <gauthier.wallet@gmail.com>
 */
class GuzzleHttp implements AdapterInterface
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @return ClientInterface
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param string $endpoint
     * @param string $content
     * @param array $headers
     * @param array $files
     * @return Response
     */
    public function send($endpoint, $content, array $headers = array(), array $files = array())
    {
        $request = $this->client->createRequest('POST', $endpoint, array(
            'body' => $content,
            'exceptions' => false,
            'headers' => $headers,
        ));

        $body = new PostBody();

        if ($files && $request instanceof RequestInterface) {
            foreach ($files as $name => $path) {
                $body->addFile(new PostFile($name, fopen($path, 'r')));
            }
        }

        $request->setBody($body);

        $response = $this->client->send($request);

        return new Response($response->getStatusCode(), $response->getBody());
    }
}
