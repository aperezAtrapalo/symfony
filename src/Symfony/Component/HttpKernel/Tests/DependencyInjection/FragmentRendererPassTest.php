<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\HttpKernel\Tests\DependencyInjection;

use Makhan\Component\HttpFoundation\Request;
use Makhan\Component\HttpKernel\DependencyInjection\FragmentRendererPass;
use Makhan\Component\HttpKernel\Fragment\FragmentRendererInterface;

class FragmentRendererPassTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests that content rendering not implementing FragmentRendererInterface
     * trigger an exception.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testContentRendererWithoutInterface()
    {
        // one service, not implementing any interface
        $services = array(
            'my_content_renderer' => array(array('alias' => 'foo')),
        );

        $definition = $this->getMock('Makhan\Component\DependencyInjection\Definition');

        $builder = $this->getMock(
            'Makhan\Component\DependencyInjection\ContainerBuilder',
            array('hasDefinition', 'findTaggedServiceIds', 'getDefinition')
        );
        $builder->expects($this->any())
            ->method('hasDefinition')
            ->will($this->returnValue(true));

        // We don't test kernel.fragment_renderer here
        $builder->expects($this->atLeastOnce())
            ->method('findTaggedServiceIds')
            ->will($this->returnValue($services));

        $builder->expects($this->atLeastOnce())
            ->method('getDefinition')
            ->will($this->returnValue($definition));

        $pass = new FragmentRendererPass();
        $pass->process($builder);
    }

    public function testValidContentRenderer()
    {
        $services = array(
            'my_content_renderer' => array(array('alias' => 'foo')),
        );

        $renderer = $this->getMock('Makhan\Component\DependencyInjection\Definition');
        $renderer
            ->expects($this->once())
            ->method('addMethodCall')
            ->with('addRendererService', array('foo', 'my_content_renderer'))
        ;

        $definition = $this->getMock('Makhan\Component\DependencyInjection\Definition');
        $definition->expects($this->atLeastOnce())
            ->method('getClass')
            ->will($this->returnValue('Makhan\Component\HttpKernel\Tests\DependencyInjection\RendererService'));
        $definition
            ->expects($this->once())
            ->method('isPublic')
            ->will($this->returnValue(true))
        ;

        $builder = $this->getMock(
            'Makhan\Component\DependencyInjection\ContainerBuilder',
            array('hasDefinition', 'findTaggedServiceIds', 'getDefinition')
        );
        $builder->expects($this->any())
            ->method('hasDefinition')
            ->will($this->returnValue(true));

        // We don't test kernel.fragment_renderer here
        $builder->expects($this->atLeastOnce())
            ->method('findTaggedServiceIds')
            ->will($this->returnValue($services));

        $builder->expects($this->atLeastOnce())
            ->method('getDefinition')
            ->will($this->onConsecutiveCalls($renderer, $definition));

        $pass = new FragmentRendererPass();
        $pass->process($builder);
    }
}

class RendererService implements FragmentRendererInterface
{
    public function render($uri, Request $request = null, array $options = array())
    {
    }

    public function getName()
    {
        return 'test';
    }
}
