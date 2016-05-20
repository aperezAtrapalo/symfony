<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Asset\Tests;

use Makhan\Component\Asset\Package;
use Makhan\Component\Asset\VersionStrategy\StaticVersionStrategy;
use Makhan\Component\Asset\VersionStrategy\EmptyVersionStrategy;

class PackageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getConfigs
     */
    public function testGetUrl($version, $format, $path, $expected)
    {
        $package = new Package($version ? new StaticVersionStrategy($version, $format) : new EmptyVersionStrategy());
        $this->assertEquals($expected, $package->getUrl($path));
    }

    public function getConfigs()
    {
        return array(
            array('v1', '', 'http://example.com/foo', 'http://example.com/foo'),
            array('v1', '', 'https://example.com/foo', 'https://example.com/foo'),
            array('v1', '', '//example.com/foo', '//example.com/foo'),

            array('v1', '', '/foo', '/foo?v1'),
            array('v1', '', 'foo', 'foo?v1'),

            array(null, '', '/foo', '/foo'),
            array(null, '', 'foo', 'foo'),

            array('v1', 'version-%2$s/%1$s', '/foo', '/version-v1/foo'),
            array('v1', 'version-%2$s/%1$s', 'foo', 'version-v1/foo'),
            array('v1', 'version-%2$s/%1$s', 'foo/', 'version-v1/foo/'),
            array('v1', 'version-%2$s/%1$s', '/foo/', '/version-v1/foo/'),
        );
    }

    public function testGetVersion()
    {
        $package = new Package(new StaticVersionStrategy('v1'));
        $this->assertEquals('v1', $package->getVersion('/foo'));
    }
}
