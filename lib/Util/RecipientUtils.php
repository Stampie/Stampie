<?php

namespace Stampie\Util;

use Stampie\Recipient;
use Stampie\RecipientInterface;

/**
 * Stampie Recipient utility functions.
 *
 * @author Christophe Coevoet <stof@notk.org>
 */
class RecipientUtils
{
    /**
     * This class should not be instantiated.
     */
    private function __construct()
    {
    }

    /**
     * @param RecipientInterface|string $recipient
     *
     * @return RecipientInterface
     */
    public static function normalizeRecipient($recipient)
    {
        if (!$recipient instanceof RecipientInterface) {
            $recipient = new Recipient($recipient);
        }

        return $recipient;
    }

    /**
     * @param RecipientInterface[]|string $recipients
     *
     * @return RecipientInterface[]
     */
    public static function normalizeRecipients($recipients)
    {
        if (null === $recipients) {
            return [];
        }

        if (is_string($recipients)) {
            $recipients = [self::normalizeRecipient($recipients)];
        }

        if (!is_array($recipients) && $recipients instanceof RecipientInterface) {
            $recipients = [$recipients];
        }

        return $recipients;
    }

    /**
     * @param RecipientInterface[]|RecipientInterface|string $recipients
     *
     * @return string
     */
    public static function buildRecipientString($recipients)
    {
        if (null === $recipients) {
            return '';
        }

        if (is_string($recipients)) {
            return $recipients;
        }

        if ($recipients instanceof RecipientInterface) {
            $recipients = [$recipients];
        }

        $stringRecipients = [];

        foreach ($recipients as $recipient) {
            if (null === $recipient->getName()) {
                $stringRecipients[] = $recipient->getEmail();
                continue;
            }

            $stringRecipients[] = sprintf('%s <%s>', $recipient->getName(), $recipient->getEmail());
        }

        return implode(',', $stringRecipients);
    }
}
