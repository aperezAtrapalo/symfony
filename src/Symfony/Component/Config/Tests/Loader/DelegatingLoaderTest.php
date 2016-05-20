<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Config\Tests\Loader;

use Makhan\Component\Config\Loader\LoaderResolver;
use Makhan\Component\Config\Loader\DelegatingLoader;

class DelegatingLoaderTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $loader = new DelegatingLoader($resolver = new LoaderResolver());
        $this->assertTrue(true, '__construct() takes a loader resolver as its first argument');
    }

    public function testGetSetResolver()
    {
        $resolver = new LoaderResolver();
        $loader = new DelegatingLoader($resolver);
        $this->assertSame($resolver, $loader->getResolver(), '->getResolver() gets the resolver loader');
        $loader->setResolver($resolver = new LoaderResolver());
        $this->assertSame($resolver, $loader->getResolver(), '->setResolver() sets the resolver loader');
    }

    public function testSupports()
    {
        $loader1 = $this->getMock('Makhan\Component\Config\Loader\LoaderInterface');
        $loader1->expects($this->once())->method('supports')->will($this->returnValue(true));
        $loader = new DelegatingLoader(new LoaderResolver(array($loader1)));
        $this->assertTrue($loader->supports('foo.xml'), '->supports() returns true if the resource is loadable');

        $loader1 = $this->getMock('Makhan\Component\Config\Loader\LoaderInterface');
        $loader1->expects($this->once())->method('supports')->will($this->returnValue(false));
        $loader = new DelegatingLoader(new LoaderResolver(array($loader1)));
        $this->assertFalse($loader->supports('foo.foo'), '->supports() returns false if the resource is not loadable');
    }

    public function testLoad()
    {
        $loader = $this->getMock('Makhan\Component\Config\Loader\LoaderInterface');
        $loader->expects($this->once())->method('supports')->will($this->returnValue(true));
        $loader->expects($this->once())->method('load');
        $resolver = new LoaderResolver(array($loader));
        $loader = new DelegatingLoader($resolver);

        $loader->load('foo');
    }

    /**
     * @expectedException \Makhan\Component\Config\Exception\FileLoaderLoadException
     */
    public function testLoadThrowsAnExceptionIfTheResourceCannotBeLoaded()
    {
        $loader = $this->getMock('Makhan\Component\Config\Loader\LoaderInterface');
        $loader->expects($this->once())->method('supports')->will($this->returnValue(false));
        $resolver = new LoaderResolver(array($loader));
        $loader = new DelegatingLoader($resolver);

        $loader->load('foo');
    }
}
