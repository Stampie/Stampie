<?php

namespace Stampie\Util;

use Stampie\Identity;
use Stampie\IdentityInterface;

/**
 * Stampie Identity utility functions
 *
 * @author Christophe Coevoet <stof@notk.org>
 */
class IdentityUtils
{
    /**
     * This class should not be instantiated
     */
    private function __construct() {}

    /**
     * @param IdentityInterface|string $identity
     *
     * @return IdentityInterface
     */
    public static function normalizeIdentity($identity)
    {
        if (!$identity instanceof IdentityInterface) {
            $identity = new Identity($identity);
        }

        return $identity;
    }

    /**
     * @param IdentityInterface[]|string $identities
     *
     * @return IdentityInterface[]
     */
    public static function normalizeIdentities($identities)
    {
        if (null === $identities) {
            return array();
        }

        if (is_string($identities)) {
            $identities = array(self::normalizeIdentity($identities));
        }

        if (!is_array($identities) && $identities instanceof IdentityInterface) {
            $identities = array($identities);
        }

        return $identities;
    }

    /**
     * @param IdentityInterface[]|IdentityInterface|string $identities
     *
     * @return string
     */
    public static function buildIdentityString($identities)
    {
        if (null === $identities) {
            return '';
        }

        if (is_string($identities)) {
            return $identities;
        }

        if ($identities instanceof IdentityInterface) {
            $identities = array($identities);
        }

        $stringIdentities = array();

        foreach ($identities as $identity) {
            if (null === $identity->getName()) {
                $stringIdentities[] = $identity->getEmail();
                continue;
            }

            $stringIdentities[] = sprintf('%s <%s>', $identity->getName(), $identity->getEmail());
        }

        return implode(',', $stringIdentities);
    }
}
