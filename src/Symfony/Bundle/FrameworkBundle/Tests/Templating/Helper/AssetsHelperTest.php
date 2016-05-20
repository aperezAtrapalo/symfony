<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\FrameworkBundle\Tests\Templating\Helper;

use Makhan\Bundle\FrameworkBundle\Templating\Helper\AssetsHelper;
use Makhan\Component\Asset\Package;
use Makhan\Component\Asset\Packages;
use Makhan\Component\Asset\VersionStrategy\StaticVersionStrategy;

class AssetsHelperTest extends \PHPUnit_Framework_TestCase
{
    private $helper;

    protected function setUp()
    {
        $fooPackage = new Package(new StaticVersionStrategy('42', '%s?v=%s'));
        $barPackage = new Package(new StaticVersionStrategy('22', '%s?%s'));

        $packages = new Packages($fooPackage, ['bar' => $barPackage]);

        $this->helper = new AssetsHelper($packages);
    }

    public function testGetUrl()
    {
        $this->assertEquals('me.png?v=42', $this->helper->getUrl('me.png'));
        $this->assertEquals('me.png?22', $this->helper->getUrl('me.png', 'bar'));
    }

    public function testGetVersion()
    {
        $this->assertEquals('42', $this->helper->getVersion('/'));
        $this->assertEquals('22', $this->helper->getVersion('/', 'bar'));
    }
}
