<?php

namespace Stampie\Mailer;

use Stampie\Mailer;
use Stampie\MessageInterface;
use Stampie\Adapter\ResponseInterface;
use Stampie\Exception\HttpException;
use Stampie\Exception\ApiException;

/**
 * A Mailer for MailChimp STS http://mailchimp.com/features/simple-transactional-service/
 *
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
class MailChimpSts extends Mailer
{
    /**
     * {@inheritdoc}
     *
     * Splits the ServerToken up and uses the last part as the <dc>. More information
     * is at http://apidocs.mailchimp.com/sts/rtfm/
     */
    protected function getEndpoint()
    {
        return strtr('http://<dc>.sts.mailchimp.com/1.0/SendEmail.json', array(
            '<dc>' => current(array_reverse(explode('-', $this->getServerToken())))
        ));
    }

    /**
     * {@inheritdoc}
     */
    protected function format(MessageInterface $message)
    {
        $parameters = array(
            'apikey'  => $this->getServerToken(),
            'message' => array_filter(array(
                'html'       => $message->getHtml(),
                'text'       => $message->getText(),
                'subject'    => $message->getSubject(),
                'to_email'   => $message->getTo(),
                'from_email' => $message->getFrom(),
            )),
        );

        return http_build_query($parameters);
    }

    /**
     * {@inheritdoc}
     *
     * "You can consider any non-200 HTTP response code an error - the returned data will contain more detailed information"
     */
    protected function handle(ResponseInterface $response)
    {
        $httpException = new HttpException($response->getStatusCode(), $response->getStatusText());
        $error         = json_decode($response->getContent());

        throw new ApiException($error->message, $httpException);
    }
}
