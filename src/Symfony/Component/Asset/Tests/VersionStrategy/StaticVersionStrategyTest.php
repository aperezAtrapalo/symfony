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

use Makhan\Component\Asset\VersionStrategy\StaticVersionStrategy;

class StaticVersionStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testGetVersion()
    {
        $version = 'v1';
        $path = 'test-path';
        $staticVersionStrategy = new StaticVersionStrategy($version);
        $this->assertEquals($version, $staticVersionStrategy->getVersion($path));
    }

    /**
     * @dataProvider getConfigs
     */
    public function testApplyVersion($path, $version, $format)
    {
        $staticVersionStrategy = new StaticVersionStrategy($version, $format);
        $formatted = sprintf($format ?: '%s?%s', $path, $version);
        $this->assertEquals($formatted, $staticVersionStrategy->applyVersion($path));
    }

    public function getConfigs()
    {
        return array(
            array('test-path', 'v1', null),
            array('test-path', 'v2', '%s?test%s'),
        );
    }
}
