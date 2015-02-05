<?php

/*
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Stampie;

final class Attachment
{
    private $path;
    private $name;

    /**
     * @param string $path
     * @param string $name
     */
    public function __construct($path, $name = null)
    {
        $this->path = $path;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name ?: basename($this->path);
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return (new \finfo(FILEINFO_MIME_TYPE))->file($this->path);
    }

    /**
     * @return string
     */
    public function getEncodedContent()
    {
        return base64_encode($this->getContent());
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return file_get_contents($this->path);
    }
}
