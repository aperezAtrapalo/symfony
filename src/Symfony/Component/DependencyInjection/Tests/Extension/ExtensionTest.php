<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\DependencyInjection\Tests\Extension;

class ExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getResolvedEnabledFixtures
     */
    public function testIsConfigEnabledReturnsTheResolvedValue($enabled)
    {
        $pb = $this->getMockBuilder('Makhan\Component\DependencyInjection\ParameterBag\ParameterBag')
            ->setMethods(array('resolveValue'))
            ->getMock()
        ;

        $container = $this->getMockBuilder('Makhan\Component\DependencyInjection\ContainerBuilder')
            ->setMethods(array('getParameterBag'))
            ->getMock()
        ;

        $pb->expects($this->once())
            ->method('resolveValue')
            ->with($this->equalTo($enabled))
            ->will($this->returnValue($enabled))
        ;

        $container->expects($this->once())
            ->method('getParameterBag')
            ->will($this->returnValue($pb))
        ;

        $extension = $this->getMockBuilder('Makhan\Component\DependencyInjection\Extension\Extension')
            ->setMethods(array())
            ->getMockForAbstractClass()
        ;

        $r = new \ReflectionMethod('Makhan\Component\DependencyInjection\Extension\Extension', 'isConfigEnabled');
        $r->setAccessible(true);

        $r->invoke($extension, $container, array('enabled' => $enabled));
    }

    public function getResolvedEnabledFixtures()
    {
        return array(
            array(true),
            array(false),
        );
    }

    /**
     * @expectedException \Makhan\Component\DependencyInjection\Exception\InvalidArgumentException
     * @expectedExceptionMessage The config array has no 'enabled' key.
     */
    public function testIsConfigEnabledOnNonEnableableConfig()
    {
        $container = $this->getMockBuilder('Makhan\Component\DependencyInjection\ContainerBuilder')
            ->getMock()
        ;

        $extension = $this->getMockBuilder('Makhan\Component\DependencyInjection\Extension\Extension')
            ->setMethods(array())
            ->getMockForAbstractClass()
        ;

        $r = new \ReflectionMethod('Makhan\Component\DependencyInjection\Extension\Extension', 'isConfigEnabled');
        $r->setAccessible(true);

        $r->invoke($extension, $container, array());
    }
}
