<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\FrameworkBundle\Tests\Command;

use Makhan\Component\Console\Application;
use Makhan\Component\Console\Tester\CommandTester;
use Makhan\Bundle\FrameworkBundle\Command\RouterDebugCommand;
use Makhan\Component\Routing\Route;
use Makhan\Component\Routing\RouteCollection;

class RouterDebugCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testDebugAllRoutes()
    {
        $tester = $this->createCommandTester();
        $ret = $tester->execute(array('name' => null), array('decorated' => false));

        $this->assertEquals(0, $ret, 'Returns 0 in case of success');
        $this->assertContains('Name   Method   Scheme   Host   Path', $tester->getDisplay());
    }

    public function testDebugSingleRoute()
    {
        $tester = $this->createCommandTester();
        $ret = $tester->execute(array('name' => 'foo'), array('decorated' => false));

        $this->assertEquals(0, $ret, 'Returns 0 in case of success');
        $this->assertContains('Route Name   | foo', $tester->getDisplay());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testDebugInvalidRoute()
    {
        $this->createCommandTester()->execute(array('name' => 'test'));
    }

    /**
     * @return CommandTester
     */
    private function createCommandTester()
    {
        $application = new Application();

        $command = new RouterDebugCommand();
        $command->setContainer($this->getContainer());
        $application->add($command);

        return new CommandTester($application->find('debug:router'));
    }

    private function getContainer()
    {
        $routeCollection = new RouteCollection();
        $routeCollection->add('foo', new Route('foo'));
        $router = $this->getMock('Makhan\Component\Routing\RouterInterface');
        $router
            ->expects($this->any())
            ->method('getRouteCollection')
            ->will($this->returnValue($routeCollection))
        ;

        $loader = $this->getMockBuilder('Makhan\Bundle\FrameworkBundle\Routing\DelegatingLoader')
             ->disableOriginalConstructor()
             ->getMock();

        $container = $this->getMock('Makhan\Component\DependencyInjection\ContainerInterface');
        $container
            ->expects($this->once())
            ->method('has')
            ->with('router')
            ->will($this->returnValue(true))
        ;

        $container
            ->method('get')
            ->will($this->returnValueMap(array(
                array('router', 1, $router),
                array('controller_name_converter', 1, $loader),
            )));

        return $container;
    }
}
