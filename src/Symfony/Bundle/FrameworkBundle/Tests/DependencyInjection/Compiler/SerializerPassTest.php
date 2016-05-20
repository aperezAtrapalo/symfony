<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\FrameworkBundle\Tests\DependencyInjection\Compiler;

use Makhan\Component\DependencyInjection\Reference;
use Makhan\Bundle\FrameworkBundle\DependencyInjection\Compiler\SerializerPass;

/**
 * Tests for the SerializerPass class.
 *
 * @author Javier Lopez <f12loalf@gmail.com>
 */
class SerializerPassTest extends \PHPUnit_Framework_TestCase
{
    public function testThrowExceptionWhenNoNormalizers()
    {
        $container = $this->getMock('Makhan\Component\DependencyInjection\ContainerBuilder', array('hasDefinition', 'findTaggedServiceIds'));

        $container->expects($this->once())
            ->method('hasDefinition')
            ->with('serializer')
            ->will($this->returnValue(true));

        $container->expects($this->once())
            ->method('findTaggedServiceIds')
            ->with('serializer.normalizer')
            ->will($this->returnValue(array()));

        $this->setExpectedException('RuntimeException');

        $serializerPass = new SerializerPass();
        $serializerPass->process($container);
    }

    public function testThrowExceptionWhenNoEncoders()
    {
        $definition = $this->getMock('Makhan\Component\DependencyInjection\Definition');
        $container = $this->getMock(
            'Makhan\Component\DependencyInjection\ContainerBuilder',
            array('hasDefinition', 'findTaggedServiceIds', 'getDefinition')
        );

        $container->expects($this->once())
            ->method('hasDefinition')
            ->with('serializer')
            ->will($this->returnValue(true));

        $container->expects($this->any())
            ->method('findTaggedServiceIds')
            ->will($this->onConsecutiveCalls(
                    array('n' => array('serializer.normalizer')),
                    array()
              ));

        $container->expects($this->once())
            ->method('getDefinition')
            ->will($this->returnValue($definition));

        $this->setExpectedException('RuntimeException');

        $serializerPass = new SerializerPass();
        $serializerPass->process($container);
    }

    public function testServicesAreOrderedAccordingToPriority()
    {
        $services = array(
            'n3' => array('tag' => array()),
            'n1' => array('tag' => array('priority' => 200)),
            'n2' => array('tag' => array('priority' => 100)),
        );

        $expected = array(
           new Reference('n1'),
           new Reference('n2'),
           new Reference('n3'),
       );

        $container = $this->getMock('Makhan\Component\DependencyInjection\ContainerBuilder', array('findTaggedServiceIds'));

        $container->expects($this->any())
            ->method('findTaggedServiceIds')
            ->will($this->returnValue($services));

        $serializerPass = new SerializerPass();

        $method = new \ReflectionMethod(
          'Makhan\Bundle\FrameworkBundle\DependencyInjection\Compiler\SerializerPass',
          'findAndSortTaggedServices'
        );
        $method->setAccessible(true);

        $actual = $method->invoke($serializerPass, 'tag', $container);

        $this->assertEquals($expected, $actual);
    }
}
