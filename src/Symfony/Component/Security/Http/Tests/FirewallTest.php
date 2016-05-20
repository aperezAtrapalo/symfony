<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Security\Http\Tests;

use Makhan\Component\Security\Http\Firewall;
use Makhan\Component\HttpKernel\Event\GetResponseEvent;
use Makhan\Component\HttpKernel\HttpKernelInterface;

class FirewallTest extends \PHPUnit_Framework_TestCase
{
    public function testOnKernelRequestRegistersExceptionListener()
    {
        $dispatcher = $this->getMock('Makhan\Component\EventDispatcher\EventDispatcherInterface');

        $listener = $this->getMock('Makhan\Component\Security\Http\Firewall\ExceptionListener', array(), array(), '', false);
        $listener
            ->expects($this->once())
            ->method('register')
            ->with($this->equalTo($dispatcher))
        ;

        $request = $this->getMock('Makhan\Component\HttpFoundation\Request', array(), array(), '', false, false);

        $map = $this->getMock('Makhan\Component\Security\Http\FirewallMapInterface');
        $map
            ->expects($this->once())
            ->method('getListeners')
            ->with($this->equalTo($request))
            ->will($this->returnValue(array(array(), $listener)))
        ;

        $event = new GetResponseEvent($this->getMock('Makhan\Component\HttpKernel\HttpKernelInterface'), $request, HttpKernelInterface::MASTER_REQUEST);

        $firewall = new Firewall($map, $dispatcher);
        $firewall->onKernelRequest($event);
    }

    public function testOnKernelRequestStopsWhenThereIsAResponse()
    {
        $response = $this->getMock('Makhan\Component\HttpFoundation\Response');

        $first = $this->getMock('Makhan\Component\Security\Http\Firewall\ListenerInterface');
        $first
            ->expects($this->once())
            ->method('handle')
        ;

        $second = $this->getMock('Makhan\Component\Security\Http\Firewall\ListenerInterface');
        $second
            ->expects($this->never())
            ->method('handle')
        ;

        $map = $this->getMock('Makhan\Component\Security\Http\FirewallMapInterface');
        $map
            ->expects($this->once())
            ->method('getListeners')
            ->will($this->returnValue(array(array($first, $second), null)))
        ;

        $event = $this->getMock(
            'Makhan\Component\HttpKernel\Event\GetResponseEvent',
            array('hasResponse'),
            array(
                $this->getMock('Makhan\Component\HttpKernel\HttpKernelInterface'),
                $this->getMock('Makhan\Component\HttpFoundation\Request', array(), array(), '', false, false),
                HttpKernelInterface::MASTER_REQUEST,
            )
        );
        $event
            ->expects($this->once())
            ->method('hasResponse')
            ->will($this->returnValue(true))
        ;

        $firewall = new Firewall($map, $this->getMock('Makhan\Component\EventDispatcher\EventDispatcherInterface'));
        $firewall->onKernelRequest($event);
    }

    public function testOnKernelRequestWithSubRequest()
    {
        $map = $this->getMock('Makhan\Component\Security\Http\FirewallMapInterface');
        $map
            ->expects($this->never())
            ->method('getListeners')
        ;

        $event = new GetResponseEvent(
            $this->getMock('Makhan\Component\HttpKernel\HttpKernelInterface'),
            $this->getMock('Makhan\Component\HttpFoundation\Request'),
            HttpKernelInterface::SUB_REQUEST
        );

        $firewall = new Firewall($map, $this->getMock('Makhan\Component\EventDispatcher\EventDispatcherInterface'));
        $firewall->onKernelRequest($event);

        $this->assertFalse($event->hasResponse());
    }
}
