<?php

namespace Stampie\Tests\Util;

use PHPUnit\Framework\TestCase;
use Stampie\Identity;
use Stampie\IdentityInterface;
use Stampie\Util\IdentityUtils;

/**
 * @coversDefaultClass \Stampie\Util\IdentityUtils
 */
class IdentityUtilsTest extends TestCase
{
    public function normalizeIdentityScenarios()
    {
        return [
            ['john@example.com'],
            [new Identity('john@example.com')],
        ];
    }

    public function normalizeIdentitiesScenarios()
    {
        return [
            [['john@example.com', 'bob@example.com']],
            [null],
            ['john@example.com'],
            [new Identity('john@example.com')],
        ];
    }

    public function buildIdentityStringScenarios()
    {
        return [
            [null, ''],
            ['john@example.com', 'john@example.com'],
            [new Identity('john@example.com', 'John'), 'John <john@example.com>'],
            [
                [
                    new Identity('john@example.com', 'John'),
                    new Identity('bob@example.com', 'Bob'),
                    new Identity('charlie@example.com')
                ],
                'John <john@example.com>,Bob <bob@example.com>,charlie@example.com'
            ],
        ];
    }

    /**
     * @covers ::normalizeIdentity
     * @dataProvider normalizeIdentityScenarios
     */
    public function testNormalizeIdentity($identity)
    {
        $this->assertInstanceOf(IdentityInterface::class, IdentityUtils::normalizeIdentity($identity));
    }

    /**
     * @covers ::normalizeIdentities
     * @dataProvider normalizeIdentitiesScenarios
     */
    public function testNormalizeIdentities($identities)
    {
        $normalizedIdentities = IdentityUtils::normalizeIdentities($identities);
        $this->assertInternalType('array', $normalizedIdentities);
        foreach ($normalizedIdentities as $identity) {
            $this->assertInstanceOf(IdentityInterface::class, $identity);
        }
    }

    /**
     * @covers ::buildIdentityString
     * @dataProvider buildIdentityStringScenarios
     */
    public function testBuildIdentityString($identities, $expected)
    {
        $this->assertSame($expected, IdentityUtils::buildIdentityString($identities));
    }
}
