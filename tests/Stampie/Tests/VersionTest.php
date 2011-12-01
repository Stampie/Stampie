<?php

namespace Stampie\Tests;

use Stampie\Version;

class VersionTest extends \PHPUnit_Framework_TestCase
{
    public function testVersionConstantIsTheRightFormat()
    {
        $constant = Version::VERSION;

        $this->assertEquals(1, preg_match('/^v?\d+\.\d+\.\d+((\-)(\w+(\.\w+)?))?$/', $constant));
    }
}
