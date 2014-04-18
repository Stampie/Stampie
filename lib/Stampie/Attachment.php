<?php

namespace Stampie;

/**
 * An Attachment is a container for a file that will be included with a Message.
 *
 * @author Adam Averay <adam@averay.com>
 */
class Attachment
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string|null
     */
    protected $id;

    /**
     * @param string $path
     * @param string|null $name
     * @param string|null $type
     * @param string|null $id
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function __construct($path, $name = null, $type = null, $id = null)
    {
        if (!$this->isValidFile($path)) {
            throw new \InvalidArgumentException('Cannot read file');
        }

        $this->path = $path;
        $this->name = (isset($name) ? $name : basename($path));
        $this->id   = $id;

        if ($type === null) {
            $type = $this->determineFileType($path);
            if (!isset($type)) {
                throw new \RuntimeException('Cannot determine file type');
            }
        }

        $this->type = $type;
    }

    /**
     * @param string $path
     * @return bool
     */
    protected function isValidFile($path)
    {
        return (file_exists($path) && is_readable($path));
    }

    /**
     * @param string $path
     * @return string
     * @throws \RuntimeException
     */
    protected function determineFileType($path)
    {
        if (!function_exists('finfo_open')) {
            // File info functions not available
            return null;
        }

        // Determine file type
        $finfo = finfo_open(\FILEINFO_MIME_TYPE);
        $type  = finfo_file($finfo, $path);


        finfo_close($finfo);

        if ($type === false) {
            // Could not determine file type
            return null;
        }

        return $type;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getId()
    {
        return $this->id;
    }
}
