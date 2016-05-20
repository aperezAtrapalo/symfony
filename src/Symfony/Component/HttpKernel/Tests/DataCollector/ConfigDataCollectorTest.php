<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\HttpKernel\Tests\DataCollector;

use Makhan\Component\HttpKernel\DataCollector\ConfigDataCollector;
use Makhan\Component\HttpKernel\Kernel;
use Makhan\Component\Config\Loader\LoaderInterface;
use Makhan\Component\HttpFoundation\Request;
use Makhan\Component\HttpFoundation\Response;

class ConfigDataCollectorTest extends \PHPUnit_Framework_TestCase
{
    public function testCollect()
    {
        $kernel = new KernelForTest('test', true);
        $c = new ConfigDataCollector();
        $c->setCacheVersionInfo(false);
        $c->setKernel($kernel);
        $c->collect(new Request(), new Response());

        $this->assertSame('test', $c->getEnv());
        $this->assertTrue($c->isDebug());
        $this->assertSame('config', $c->getName());
        $this->assertSame('testkernel', $c->getAppName());
        $this->assertSame(PHP_VERSION, $c->getPhpVersion());
        $this->assertSame(Kernel::VERSION, $c->getMakhanVersion());
        $this->assertNull($c->getToken());

        // if else clause because we don't know it
        if (extension_loaded('xdebug')) {
            $this->assertTrue($c->hasXDebug());
        } else {
            $this->assertFalse($c->hasXDebug());
        }

        // if else clause because we don't know it
        if (((extension_loaded('eaccelerator') && ini_get('eaccelerator.enable'))
                ||
                (extension_loaded('apc') && ini_get('apc.enabled'))
                ||
                (extension_loaded('Zend OPcache') && ini_get('opcache.enable'))
                ||
                (extension_loaded('xcache') && ini_get('xcache.cacher'))
                ||
                (extension_loaded('wincache') && ini_get('wincache.ocenabled')))) {
            $this->assertTrue($c->hasAccelerator());
        } else {
            $this->assertFalse($c->hasAccelerator());
        }
    }
}

class KernelForTest extends Kernel
{
    public function getName()
    {
        return 'testkernel';
    }

    public function registerBundles()
    {
    }

    public function getBundles()
    {
        return array();
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
    }
}
