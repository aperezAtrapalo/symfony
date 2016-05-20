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

use Makhan\Bundle\FrameworkBundle\DependencyInjection\Compiler\LoggingTranslatorPass;

class LoggingTranslatorPassTest extends \PHPUnit_Framework_TestCase
{
    public function testProcess()
    {
        $definition = $this->getMock('Makhan\Component\DependencyInjection\Definition');
        $container = $this->getMock('Makhan\Component\DependencyInjection\ContainerBuilder');
        $parameterBag = $this->getMock('Makhan\Component\DependencyInjection\ParameterBag\ParameterBagInterface');

        $container->expects($this->exactly(2))
            ->method('hasAlias')
            ->will($this->returnValue(true));

        $container->expects($this->once())
            ->method('getParameter')
            ->will($this->returnValue(true));

        $container->expects($this->once())
            ->method('getAlias')
            ->will($this->returnValue('translation.default'));

        $container->expects($this->exactly(3))
            ->method('getDefinition')
            ->will($this->returnValue($definition));

        $container->expects($this->once())
            ->method('hasParameter')
            ->with('translator.logging')
            ->will($this->returnValue(true));

        $definition->expects($this->once())
            ->method('getClass')
            ->will($this->returnValue('Makhan\Bundle\FrameworkBundle\Translation\Translator'));

        $parameterBag->expects($this->once())
            ->method('resolveValue')
            ->will($this->returnValue("Makhan\Bundle\FrameworkBundle\Translation\Translator"));

        $container->expects($this->once())
            ->method('getParameterBag')
            ->will($this->returnValue($parameterBag));

        $pass = new LoggingTranslatorPass();
        $pass->process($container);
    }

    public function testThatCompilerPassIsIgnoredIfThereIsNotLoggerDefinition()
    {
        $container = $this->getMock('Makhan\Component\DependencyInjection\ContainerBuilder');
        $container->expects($this->once())
            ->method('hasAlias')
            ->will($this->returnValue(false));

        $pass = new LoggingTranslatorPass();
        $pass->process($container);
    }

    public function testThatCompilerPassIsIgnoredIfThereIsNotTranslatorDefinition()
    {
        $container = $this->getMock('Makhan\Component\DependencyInjection\ContainerBuilder');
        $container->expects($this->at(0))
            ->method('hasAlias')
            ->will($this->returnValue(true));

        $container->expects($this->at(0))
            ->method('hasAlias')
            ->will($this->returnValue(false));

        $pass = new LoggingTranslatorPass();
        $pass->process($container);
    }
}
