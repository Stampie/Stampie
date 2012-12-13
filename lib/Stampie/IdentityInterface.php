<?php

namespace Stampie;

/**
 * @author Christophe Coevoet <stof@notk.org>
 */
interface IdentityInterface
{
    /**
     * @return string
     */
    public function getEmail();

    /**
     * @return string|null
     */
    public function getName();
}
