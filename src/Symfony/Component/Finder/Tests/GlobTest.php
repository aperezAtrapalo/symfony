<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Finder\Tests;

use Makhan\Component\Finder\Glob;

class GlobTest extends \PHPUnit_Framework_TestCase
{
    public function testGlobToRegexDelimiters()
    {
        $this->assertEquals('#^(?=[^\.])\#$#', Glob::toRegex('#'));
        $this->assertEquals('#^\.[^/]*$#', Glob::toRegex('.*'));
        $this->assertEquals('^\.[^/]*$', Glob::toRegex('.*', true, true, ''));
        $this->assertEquals('/^\.[^/]*$/', Glob::toRegex('.*', true, true, '/'));
    }
}
