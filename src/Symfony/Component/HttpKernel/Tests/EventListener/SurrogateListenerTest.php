<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\HttpKernel\Tests\EventListener;

use Makhan\Component\HttpKernel\HttpCache\Esi;
use Makhan\Component\HttpKernel\EventListener\SurrogateListener;
use Makhan\Component\HttpKernel\Event\FilterResponseEvent;
use Makhan\Component\HttpKernel\KernelEvents;
use Makhan\Component\HttpKernel\HttpKernelInterface;
use Makhan\Component\HttpFoundation\Response;
use Makhan\Component\HttpFoundation\Request;
use Makhan\Component\EventDispatcher\EventDispatcher;

class SurrogateListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testFilterDoesNothingForSubRequests()
    {
        $dispatcher = new EventDispatcher();
        $kernel = $this->getMock('Makhan\Component\HttpKernel\HttpKernelInterface');
        $response = new Response('foo <esi:include src="" />');
        $listener = new SurrogateListener(new Esi());

        $dispatcher->addListener(KernelEvents::RESPONSE, array($listener, 'onKernelResponse'));
        $event = new FilterResponseEvent($kernel, new Request(), HttpKernelInterface::SUB_REQUEST, $response);
        $dispatcher->dispatch(KernelEvents::RESPONSE, $event);

        $this->assertEquals('', $event->getResponse()->headers->get('Surrogate-Control'));
    }

    public function testFilterWhenThereIsSomeEsiIncludes()
    {
        $dispatcher = new EventDispatcher();
        $kernel = $this->getMock('Makhan\Component\HttpKernel\HttpKernelInterface');
        $response = new Response('foo <esi:include src="" />');
        $listener = new SurrogateListener(new Esi());

        $dispatcher->addListener(KernelEvents::RESPONSE, array($listener, 'onKernelResponse'));
        $event = new FilterResponseEvent($kernel, new Request(), HttpKernelInterface::MASTER_REQUEST, $response);
        $dispatcher->dispatch(KernelEvents::RESPONSE, $event);

        $this->assertEquals('content="ESI/1.0"', $event->getResponse()->headers->get('Surrogate-Control'));
    }

    public function testFilterWhenThereIsNoEsiIncludes()
    {
        $dispatcher = new EventDispatcher();
        $kernel = $this->getMock('Makhan\Component\HttpKernel\HttpKernelInterface');
        $response = new Response('foo');
        $listener = new SurrogateListener(new Esi());

        $dispatcher->addListener(KernelEvents::RESPONSE, array($listener, 'onKernelResponse'));
        $event = new FilterResponseEvent($kernel, new Request(), HttpKernelInterface::MASTER_REQUEST, $response);
        $dispatcher->dispatch(KernelEvents::RESPONSE, $event);

        $this->assertEquals('', $event->getResponse()->headers->get('Surrogate-Control'));
    }
}
