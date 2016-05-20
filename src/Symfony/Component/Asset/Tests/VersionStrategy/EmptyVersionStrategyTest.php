<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Asset\Tests\VersionStrategy;

use Makhan\Component\Asset\VersionStrategy\EmptyVersionStrategy;

class EmptyVersionStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testGetVersion()
    {
        $emptyVersionStrategy = new EmptyVersionStrategy();
        $path = 'test-path';

        $this->assertEmpty($emptyVersionStrategy->getVersion($path));
    }

    public function testApplyVersion()
    {
        $emptyVersionStrategy = new EmptyVersionStrategy();
        $path = 'test-path';

        $this->assertEquals($path, $emptyVersionStrategy->applyVersion($path));
    }
}
