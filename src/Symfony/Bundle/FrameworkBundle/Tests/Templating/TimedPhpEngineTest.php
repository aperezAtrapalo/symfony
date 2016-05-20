<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\FrameworkBundle\Tests\Templating;

use Makhan\Bundle\FrameworkBundle\Templating\TimedPhpEngine;
use Makhan\Component\DependencyInjection\Container;
use Makhan\Bundle\FrameworkBundle\Templating\GlobalVariables;
use Makhan\Bundle\FrameworkBundle\Tests\TestCase;

class TimedPhpEngineTest extends TestCase
{
    public function testThatRenderLogsTime()
    {
        $container = $this->getContainer();
        $templateNameParser = $this->getTemplateNameParser();
        $globalVariables = $this->getGlobalVariables();
        $loader = $this->getLoader($this->getStorage());

        $stopwatch = $this->getStopwatch();
        $stopwatchEvent = $this->getStopwatchEvent();

        $stopwatch->expects($this->once())
            ->method('start')
            ->with('template.php (index.php)', 'template')
            ->will($this->returnValue($stopwatchEvent));

        $stopwatchEvent->expects($this->once())->method('stop');

        $engine = new TimedPhpEngine($templateNameParser, $container, $loader, $stopwatch, $globalVariables);
        $engine->render('index.php');
    }

    /**
     * @return Container
     */
    private function getContainer()
    {
        return $this->getMock('Makhan\Component\DependencyInjection\Container');
    }

    /**
     * @return \Makhan\Component\Templating\TemplateNameParserInterface
     */
    private function getTemplateNameParser()
    {
        $templateReference = $this->getMock('Makhan\Component\Templating\TemplateReferenceInterface');
        $templateNameParser = $this->getMock('Makhan\Component\Templating\TemplateNameParserInterface');
        $templateNameParser->expects($this->any())
            ->method('parse')
            ->will($this->returnValue($templateReference));

        return $templateNameParser;
    }

    /**
     * @return GlobalVariables
     */
    private function getGlobalVariables()
    {
        return $this->getMockBuilder('Makhan\Bundle\FrameworkBundle\Templating\GlobalVariables')
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return \Makhan\Component\Templating\Storage\StringStorage
     */
    private function getStorage()
    {
        return $this->getMockBuilder('Makhan\Component\Templating\Storage\StringStorage')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
    }

    /**
     * @param \Makhan\Component\Templating\Storage\StringStorage $storage
     *
     * @return \Makhan\Component\Templating\Loader\Loader
     */
    private function getLoader($storage)
    {
        $loader = $this->getMockForAbstractClass('Makhan\Component\Templating\Loader\Loader');
        $loader->expects($this->once())
            ->method('load')
            ->will($this->returnValue($storage));

        return $loader;
    }

    /**
     * @return \Makhan\Component\Stopwatch\StopwatchEvent
     */
    private function getStopwatchEvent()
    {
        return $this->getMockBuilder('Makhan\Component\Stopwatch\StopwatchEvent')
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return \Makhan\Component\Stopwatch\Stopwatch
     */
    private function getStopwatch()
    {
        return $this->getMock('Makhan\Component\Stopwatch\Stopwatch');
    }
}
