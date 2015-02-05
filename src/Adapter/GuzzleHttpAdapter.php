<?php

/*
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Stampie\Adapter;

use GuzzleHttp\ClientInterface;
use Stampie\Attachment;

class GuzzleHttpAdapter implements Stampie\Adapter
{
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritDoc}
     */
    public function request(Request $request, $attachments = [])
    {
        $request = $this->client->createRequest($request->getMethod(), $request->getUrl(), [
            'body'    => $request->getContent(),
            'headers' => $request->getHeaders(),
        ]);

        $body = $request->getBody();

        foreach ($attachments as $attachment) {
            $body->addFile($this->convertAttachmentToPostFile($attachment));
        }

        $response = $client->send($request);

        return new Response($response->getStatusCode(), $response->getBody(), $response->getHeaders());
    }

    private function convertAttachmentToPostFile(Attachment $attachment)
    {
    }
}
