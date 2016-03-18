<?php
/**
 * Created by PhpStorm.
 * User: darren
 * Date: 18/03/2016
 * Time: 08:34
 */

namespace Stampie\Adapter;


use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;

class Guzzle6 implements AdapterInterface
{
    /** @var ClientInterface $client */
    protected $client;

    /**
     * Guzzle6 constructor.
     * @param $client
     */
    public function __construct($client)
    {
        $this->client = $client;
    }


    function send($endpoint, $content, array $headers = array(), array $files = array())
    {

        $request = new Request('POST', $endpoint, $headers, $content);


        // TODO: handle files
        //$body = new PostBody();
        //
        //if ($files && $request instanceof RequestInterface) {
        //    foreach ($files as $name => $path) {
        //        $body->addFile(new PostFile($name, fopen($path, 'r')));
        //    }
        //}
        //
        //$request->setBody($body);
        
        $response = $this->client->send($request);

        return new Response($response->getStatusCode(), $response->getBody());
    }


}