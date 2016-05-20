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
use Makhan\Bundle\FrameworkBundle\DependencyInjection\Compiler\TranslatorPass;

class TranslatorPassTest extends \PHPUnit_Framework_TestCase
{
    public function testValidCollector()
    {
        $definition = $this->getMock('Makhan\Component\DependencyInjection\Definition');
        $definition->expects($this->at(0))
            ->method('addMethodCall')
            ->with('addLoader', array('xliff', new Reference('xliff')));
        $definition->expects($this->at(1))
            ->method('addMethodCall')
            ->with('addLoader', array('xlf', new Reference('xliff')));

        $container = $this->getMock(
            'Makhan\Component\DependencyInjection\ContainerBuilder',
            array('hasDefinition', 'getDefinition', 'findTaggedServiceIds', 'findDefinition')
        );
        $container->expects($this->any())
            ->method('hasDefinition')
            ->will($this->returnValue(true));
        $container->expects($this->once())
            ->method('getDefinition')
            ->will($this->returnValue($definition));
        $container->expects($this->once())
            ->method('findTaggedServiceIds')
            ->will($this->returnValue(array('xliff' => array(array('alias' => 'xliff', 'legacy-alias' => 'xlf')))));
        $container->expects($this->once())
            ->method('findDefinition')
            ->will($this->returnValue($this->getMock('Makhan\Component\DependencyInjection\Definition')));
        $pass = new TranslatorPass();
        $pass->process($container);
    }
}
