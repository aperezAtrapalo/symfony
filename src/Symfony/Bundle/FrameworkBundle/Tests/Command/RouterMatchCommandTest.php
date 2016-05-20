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
use Makhan\Bundle\FrameworkBundle\Command\RouterMatchCommand;
use Makhan\Bundle\FrameworkBundle\Command\RouterDebugCommand;
use Makhan\Component\Routing\Route;
use Makhan\Component\Routing\RouteCollection;
use Makhan\Component\Routing\RequestContext;

class RouterMatchCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testWithMatchPath()
    {
        $tester = $this->createCommandTester();
        $ret = $tester->execute(array('path_info' => '/foo', 'foo'), array('decorated' => false));

        $this->assertEquals(0, $ret, 'Returns 0 in case of success');
        $this->assertContains('Route Name   | foo', $tester->getDisplay());
    }

    public function testWithNotMatchPath()
    {
        $tester = $this->createCommandTester();
        $ret = $tester->execute(array('path_info' => '/test', 'foo'), array('decorated' => false));

        $this->assertEquals(1, $ret, 'Returns 1 in case of failure');
        $this->assertContains('None of the routes match the path "/test"', $tester->getDisplay());
    }

    /**
     * @return CommandTester
     */
    private function createCommandTester()
    {
        $application = new Application();

        $command = new RouterMatchCommand();
        $command->setContainer($this->getContainer());
        $application->add($command);

        $command = new RouterDebugCommand();
        $command->setContainer($this->getContainer());
        $application->add($command);

        return new CommandTester($application->find('router:match'));
    }

    private function getContainer()
    {
        $routeCollection = new RouteCollection();
        $routeCollection->add('foo', new Route('foo'));
        $requestContext = new RequestContext();
        $router = $this->getMock('Makhan\Component\Routing\RouterInterface');
        $router
            ->expects($this->any())
            ->method('getRouteCollection')
            ->will($this->returnValue($routeCollection))
        ;
        $router
            ->expects($this->any())
            ->method('getContext')
            ->will($this->returnValue($requestContext))
        ;

        $loader = $this->getMockBuilder('Makhan\Bundle\FrameworkBundle\Routing\DelegatingLoader')
             ->disableOriginalConstructor()
             ->getMock();

        $container = $this->getMock('Makhan\Component\DependencyInjection\ContainerInterface');
        $container
            ->expects($this->once())
            ->method('has')
            ->with('router')
            ->will($this->returnValue(true));
        $container->method('get')
            ->will($this->returnValueMap(array(
                array('router', 1, $router),
                array('controller_name_converter', 1, $loader),

            )));

        return $container;
    }
}
