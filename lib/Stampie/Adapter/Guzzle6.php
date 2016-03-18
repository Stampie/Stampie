<?php

namespace Stampie\Adapter;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;

/**
 * GuzzleHttp Adapter (guzzlephp.org) - Version 6+
 *
 */
class Guzzle6 implements AdapterInterface
{
    /** @var ClientInterface $client */
    private $client;

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

    function send($endpoint, $content, array $headers = array(), array $files = array())
    {

        $request = new Request('POST', $endpoint, $headers, $content);

        // TODO: handle files
        
        $response = $this->client->send($request);

        return new Response($response->getStatusCode(), $response->getBody());
    }


}