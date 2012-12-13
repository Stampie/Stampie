<?php

namespace Stampie\Message;

/**
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
interface TaggableInterface
{
    /**
     * @return string|array
     */
    public function getTag();
}
