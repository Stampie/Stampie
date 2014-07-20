<?php

namespace Stampie;

final class Attachment
{
    private $path;
    private $name;

    public function __construct($path, $name = null)
    {
        $this->path = $path;
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name ?: basename($this->path);
    }

    public function getContentType()
    {
        $file = new \finfo(FILEINFO_MIME_TYPE);

        return $file->file($this->path);
    }

    public function getEncodedContent()
    {
        return base64_encode($this->getContent());
    }

    public function getContent()
    {
        return file_get_contents($this->path);
    }
}
