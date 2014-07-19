<?php

namespace Stampie;

class Attachment
{
    public function getName()
    {
        return 'myattachment.jpg';
    }

    public function getContentType()
    {
        return 'image/jpeg';
    }

    public function getEncodedContent()
    {
        return '';
    }
}
