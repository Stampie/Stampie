<?php

namespace Stampie\Message;

/**
 * @author Christophe Coevoet <stof@notk.org>
 */
interface MetadataAwareInterface
{
    /**
     * Gets the metadata attached to the message.
     *
     * Message metadata allow to attach some additional information to the message.
     * This can typically be used to attach a customer id to the sent messages.
     *
     * Providers supporting this feature will then give some filtering or tracking
     * capabilities.
     *
     * @return array An associative array of metadata
     */
    public function getMetadata();
}
