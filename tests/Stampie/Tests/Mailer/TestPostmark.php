<?php

namespace Stampie\Tests\Mailer;

use Stampie\Adapter\ResponseInterface;
use Stampie\Mailer\Postmark;
use Stampie\MessageInterface;

class TestPostmark extends Postmark
{
    public function getEndpoint()
    {
        return parent::getEndpoint();
    }

    public function handle(ResponseInterface $response)
    {
        parent::handle($response);
    }

    public function getHeaders()
    {
        return parent::getHeaders();
    }

    public function format(MessageInterface $message)
    {
        return parent::format($message);
    }
}
