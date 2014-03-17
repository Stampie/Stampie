<?php

namespace Stampie;

/**
 * Represents an Attachment. An Attachment is a container for a file
 * that will be included with a Message.
 *
 * @author Adam Averay <adam@averay.com>
 */
interface AttachmentInterface
{
    /**
     * @return string    The path to the file
     */
    function getPath();

    /**
     * @return string    The name for the file in the message
     */
    function getName();

    /**
     * @return string    The MIME content type for the file
     */
    function getType();

    /**
     * @return string|null    The content ID for the file if available
     */
    function getId();
}
