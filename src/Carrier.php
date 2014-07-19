<?php

namespace Stampie;

interface Carrier
{
    public function createRequest(Identity $to, Message $message);

    public function handleResponse(Response $response);
}
