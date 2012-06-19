<?php

namespace Stampie\Adapter;

/**
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
class NoopAdapter implements AdapterInterface
{
    /**
     * {@inheritdoc}
     */
    public function send($endpoint, $content, array $headers = array())
    {
        return new Response(200, 'Message was sent [NoopAdapter]');
    }
}
