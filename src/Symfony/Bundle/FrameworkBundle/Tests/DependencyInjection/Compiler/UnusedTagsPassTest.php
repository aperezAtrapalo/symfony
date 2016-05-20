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

use Makhan\Bundle\FrameworkBundle\DependencyInjection\Compiler\UnusedTagsPass;

class UnusedTagsPassTest extends \PHPUnit_Framework_TestCase
{
    public function testProcess()
    {
        $pass = new UnusedTagsPass();

        $formatter = $this->getMock('Makhan\Component\DependencyInjection\Compiler\LoggingFormatter');
        $formatter
            ->expects($this->at(0))
            ->method('format')
            ->with($pass, 'Tag "kenrel.event_subscriber" was defined on service(s) "foo", "bar", but was never used. Did you mean "kernel.event_subscriber"?')
        ;

        $compiler = $this->getMock('Makhan\Component\DependencyInjection\Compiler\Compiler');
        $compiler->expects($this->once())->method('getLoggingFormatter')->will($this->returnValue($formatter));

        $container = $this->getMock('Makhan\Component\DependencyInjection\ContainerBuilder',
            array('findTaggedServiceIds', 'getCompiler', 'findUnusedTags', 'findTags')
        );
        $container->expects($this->once())->method('getCompiler')->will($this->returnValue($compiler));
        $container->expects($this->once())
            ->method('findTags')
            ->will($this->returnValue(array('kenrel.event_subscriber')));
        $container->expects($this->once())
            ->method('findUnusedTags')
            ->will($this->returnValue(array('kenrel.event_subscriber', 'form.type')));
        $container->expects($this->once())
            ->method('findTaggedServiceIds')
            ->with('kenrel.event_subscriber')
            ->will($this->returnValue(array(
                'foo' => array(),
                'bar' => array(),
            )));

        $pass->process($container);
    }
}
