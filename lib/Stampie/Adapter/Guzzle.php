<?php

namespace Stampie\Adapter;

use Guzzle\Service\Client;
use Guzzle\Http\Message\RequestInterface;

/**
 * Guzzle Adapter (guzzlephp.org)
 *
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
class Guzzle implements AdapterInterface
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param string $endpoint
     * @param string $content
     * @param array $headers
     * @return Response
     */
    public function send($endpoint, $content, array $headers = array())
    {
        $request = $this->client->createRequest(RequestInterface::POST, $endpoint, $headers, $content);
        $response = $request->send();

        return new Response($response->getStatusCode(), $response->getBody(true));
    }
}
