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
use Makhan\Component\Asset\Packages;
use Makhan\Component\Asset\VersionStrategy\StaticVersionStrategy;
use Makhan\Component\Asset\Exception\InvalidArgumentException;
use Makhan\Component\Asset\Exception\LogicException;

class PackagesTest extends \PHPUnit_Framework_TestCase
{
    public function testGetterSetters()
    {
        $packages = new Packages();
        $packages->setDefaultPackage($default = $this->getMock('Makhan\Component\Asset\PackageInterface'));
        $packages->addPackage('a', $a = $this->getMock('Makhan\Component\Asset\PackageInterface'));

        $this->assertEquals($default, $packages->getPackage());
        $this->assertEquals($a, $packages->getPackage('a'));

        $packages = new Packages($default, array('a' => $a));

        $this->assertEquals($default, $packages->getPackage());
        $this->assertEquals($a, $packages->getPackage('a'));
    }

    public function testGetVersion()
    {
        $packages = new Packages(
            new Package(new StaticVersionStrategy('default')),
            array('a' => new Package(new StaticVersionStrategy('a')))
        );

        $this->assertEquals('default', $packages->getVersion('/foo'));
        $this->assertEquals('a', $packages->getVersion('/foo', 'a'));
    }

    public function testGetUrl()
    {
        $packages = new Packages(
            new Package(new StaticVersionStrategy('default')),
            array('a' => new Package(new StaticVersionStrategy('a')))
        );

        $this->assertEquals('/foo?default', $packages->getUrl('/foo'));
        $this->assertEquals('/foo?a', $packages->getUrl('/foo', 'a'));
    }

    /**
     * @expectedException LogicException
     */
    public function testNoDefaultPackage()
    {
        $packages = new Packages();
        $packages->getPackage();
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testUndefinedPackage()
    {
        $packages = new Packages();
        $packages->getPackage('a');
    }
}
