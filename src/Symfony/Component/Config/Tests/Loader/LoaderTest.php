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

use Makhan\Component\Config\Loader\Loader;

class LoaderTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSetResolver()
    {
        $resolver = $this->getMock('Makhan\Component\Config\Loader\LoaderResolverInterface');

        $loader = new ProjectLoader1();
        $loader->setResolver($resolver);

        $this->assertSame($resolver, $loader->getResolver(), '->setResolver() sets the resolver loader');
    }

    public function testResolve()
    {
        $resolvedLoader = $this->getMock('Makhan\Component\Config\Loader\LoaderInterface');

        $resolver = $this->getMock('Makhan\Component\Config\Loader\LoaderResolverInterface');
        $resolver->expects($this->once())
            ->method('resolve')
            ->with('foo.xml')
            ->will($this->returnValue($resolvedLoader));

        $loader = new ProjectLoader1();
        $loader->setResolver($resolver);

        $this->assertSame($loader, $loader->resolve('foo.foo'), '->resolve() finds a loader');
        $this->assertSame($resolvedLoader, $loader->resolve('foo.xml'), '->resolve() finds a loader');
    }

    /**
     * @expectedException \Makhan\Component\Config\Exception\FileLoaderLoadException
     */
    public function testResolveWhenResolverCannotFindLoader()
    {
        $resolver = $this->getMock('Makhan\Component\Config\Loader\LoaderResolverInterface');
        $resolver->expects($this->once())
            ->method('resolve')
            ->with('FOOBAR')
            ->will($this->returnValue(false));

        $loader = new ProjectLoader1();
        $loader->setResolver($resolver);

        $loader->resolve('FOOBAR');
    }

    public function testImport()
    {
        $resolvedLoader = $this->getMock('Makhan\Component\Config\Loader\LoaderInterface');
        $resolvedLoader->expects($this->once())
            ->method('load')
            ->with('foo')
            ->will($this->returnValue('yes'));

        $resolver = $this->getMock('Makhan\Component\Config\Loader\LoaderResolverInterface');
        $resolver->expects($this->once())
            ->method('resolve')
            ->with('foo')
            ->will($this->returnValue($resolvedLoader));

        $loader = new ProjectLoader1();
        $loader->setResolver($resolver);

        $this->assertEquals('yes', $loader->import('foo'));
    }

    public function testImportWithType()
    {
        $resolvedLoader = $this->getMock('Makhan\Component\Config\Loader\LoaderInterface');
        $resolvedLoader->expects($this->once())
            ->method('load')
            ->with('foo', 'bar')
            ->will($this->returnValue('yes'));

        $resolver = $this->getMock('Makhan\Component\Config\Loader\LoaderResolverInterface');
        $resolver->expects($this->once())
            ->method('resolve')
            ->with('foo', 'bar')
            ->will($this->returnValue($resolvedLoader));

        $loader = new ProjectLoader1();
        $loader->setResolver($resolver);

        $this->assertEquals('yes', $loader->import('foo', 'bar'));
    }
}

class ProjectLoader1 extends Loader
{
    public function load($resource, $type = null)
    {
    }

    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'foo' === pathinfo($resource, PATHINFO_EXTENSION);
    }

    public function getType()
    {
    }
}
