<?php

namespace Stampie;

interface Carrier
{
    public function createRequest(Recipient $to, Message $message);

    public function handleResponse(Response $response);
}
