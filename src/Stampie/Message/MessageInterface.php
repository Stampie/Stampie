<?php

namespace Stampie\Message;

use Stampie\Identity;

/**
 * @package Stampie
 */
interface MessageInterface
{
    /**
     * @return Identity
     */
    public function getFrom();

    /**
     * @return string
     */
    public function getSubject();

    /**
     * @return string
     */
    public function getHtml();

    /**
     * @return string
     */
    public function getText();
}
