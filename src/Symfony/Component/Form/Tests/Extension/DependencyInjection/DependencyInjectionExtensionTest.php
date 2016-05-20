<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Form\Tests\Extension\DependencyInjection;

use Makhan\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Makhan\Component\Form\Extension\DependencyInjection\DependencyInjectionExtension;

class DependencyInjectionExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testGetTypeExtensions()
    {
        $container = $this->getMock('Makhan\Component\DependencyInjection\ContainerInterface');

        $typeExtension1 = $this->getMock('Makhan\Component\Form\FormTypeExtensionInterface');
        $typeExtension1->expects($this->any())
            ->method('getExtendedType')
            ->willReturn('test');
        $typeExtension2 = $this->getMock('Makhan\Component\Form\FormTypeExtensionInterface');
        $typeExtension2->expects($this->any())
            ->method('getExtendedType')
            ->willReturn('test');
        $typeExtension3 = $this->getMock('Makhan\Component\Form\FormTypeExtensionInterface');
        $typeExtension3->expects($this->any())
            ->method('getExtendedType')
            ->willReturn('other');

        $services = array(
            'extension1' => $typeExtension1,
            'extension2' => $typeExtension2,
            'extension3' => $typeExtension3,
        );

        $container->expects($this->any())
            ->method('get')
            ->willReturnCallback(function ($id) use ($services) {
                if (isset($services[$id])) {
                    return $services[$id];
                }

                throw new ServiceNotFoundException($id);
            });

        $extension = new DependencyInjectionExtension($container, array(), array('test' => array('extension1', 'extension2'), 'other' => array('extension3')), array());

        $this->assertTrue($extension->hasTypeExtensions('test'));
        $this->assertFalse($extension->hasTypeExtensions('unknown'));
        $this->assertSame(array($typeExtension1, $typeExtension2), $extension->getTypeExtensions('test'));
    }

    /**
     * @expectedException \Makhan\Component\Form\Exception\InvalidArgumentException
     */
    public function testThrowExceptionForInvalidExtendedType()
    {
        $container = $this->getMock('Makhan\Component\DependencyInjection\ContainerInterface');

        $typeExtension = $this->getMock('Makhan\Component\Form\FormTypeExtensionInterface');
        $typeExtension->expects($this->any())
            ->method('getExtendedType')
            ->willReturn('unmatched');

        $container->expects($this->any())
            ->method('get')
            ->with('extension')
            ->willReturn($typeExtension);

        $extension = new DependencyInjectionExtension($container, array(), array('test' => array('extension')), array());

        $extension->getTypeExtensions('test');
    }
}
