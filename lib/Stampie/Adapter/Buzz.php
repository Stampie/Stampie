<?php

namespace Stampie\Adapter;

use Buzz\Browser;
use Buzz\Message\Form\FormUpload;
use Buzz\Message\RequestInterface;

/**
 * Adapter for Kriss Wallsmith's Buzz library
 *
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
class Buzz implements AdapterInterface
{
    /**
     * @var Browser $browser
     */
    protected $browser;

    /**
     * @param Browser $browser
     */
    public function __construct(Browser $browser)
    {
        $this->browser = $browser;
    }

    /**
     * @return Browser
     */
    public function getBrowser()
    {
        return $this->browser;
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
        // Make headers buzz friendly
        array_walk($headers, function(&$value, $key) {
            $value = sprintf('%s: %s', $key, $value);
        });

        if ($files) {
            // HTTP query content
            parse_str($content, $fields);

            // Add files to request
            foreach ($files as $key => $items) {
                $fields[$key] = array();

                foreach ($items as $name => $item) {
                    $item = new FormUpload($item);
                    if(!is_numeric($name)){
                        $item->setName($name);
                    }

                    $fields[$key] = $item;
                }
            }

            $response = $this->browser->submit($endpoint, $fields, RequestInterface::METHOD_POST, array_values($headers));
        } else {
            // JSON content
            $response = $this->browser->post($endpoint, array_values($headers), $content);
        }

        return new Response($response->getStatusCode(), $response->getContent());
    }
}
