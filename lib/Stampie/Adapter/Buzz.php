<?php

namespace Stampie\Adapter;

use Stampie\Mailer;
use Buzz\Browser;

class Buzz implements AdapterInterface
{
    protected $browser;

    public function __construct(Browser $browser)
    {
        $this->browser = $browser;
    }

    public function getBrowser()
    {
        return $this->browser;
    }

    public function send($content, array $headers = array())
    {
        // Make headers buzz friendly
        array_walk($headers, function(&$value, $key) {
            $value = sprintf('%s: %s', $key, $value);
        });

        $headers = array_values($headers);

        $response = $this->browser->post(Mailer::ENDPOINT, $headers, $content);

        return new Response($response->getStatusCode(), $response->getContent());
    }
}
