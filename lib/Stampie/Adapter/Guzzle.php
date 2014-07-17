<?php

namespace Stampie\Adapter;

use Guzzle\Service\Client;
use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\Message\EntityEnclosingRequestInterface;

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
     * @param array $files
     * @return Response
     */
    public function send($endpoint, $content, array $headers = array(), array $files = array())
    {
        $request = $this->client->createRequest(RequestInterface::POST, $endpoint, $headers, $content, array('exceptions' => false));
        if ($files && $request instanceof EntityEnclosingRequestInterface) {
            $request->addPostFiles($files);
        }
        $response = $request->send();

        return new Response($response->getStatusCode(), $response->getBody(true));
    }
}
