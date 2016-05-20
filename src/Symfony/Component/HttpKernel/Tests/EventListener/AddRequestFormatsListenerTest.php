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

use Makhan\Component\HttpKernel\EventListener\AddRequestFormatsListener;
use Makhan\Component\HttpFoundation\Request;
use Makhan\Component\HttpKernel\KernelEvents;

/**
 * Test AddRequestFormatsListener class.
 *
 * @author Gildas Quemener <gildas.quemener@gmail.com>
 */
class AddRequestFormatsListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AddRequestFormatsListener
     */
    private $listener;

    protected function setUp()
    {
        $this->listener = new AddRequestFormatsListener(array('csv' => array('text/csv', 'text/plain')));
    }

    protected function tearDown()
    {
        $this->listener = null;
    }

    public function testIsAnEventSubscriber()
    {
        $this->assertInstanceOf('Makhan\Component\EventDispatcher\EventSubscriberInterface', $this->listener);
    }

    public function testRegisteredEvent()
    {
        $this->assertEquals(
            array(KernelEvents::REQUEST => 'onKernelRequest'),
            AddRequestFormatsListener::getSubscribedEvents()
        );
    }

    public function testSetAdditionalFormats()
    {
        $request = $this->getRequestMock();
        $event = $this->getGetResponseEventMock($request);

        $request->expects($this->once())
            ->method('setFormat')
            ->with('csv', array('text/csv', 'text/plain'));

        $this->listener->onKernelRequest($event);
    }

    protected function getRequestMock()
    {
        return $this->getMock('Makhan\Component\HttpFoundation\Request');
    }

    protected function getGetResponseEventMock(Request $request)
    {
        $event = $this
            ->getMockBuilder('Makhan\Component\HttpKernel\Event\GetResponseEvent')
            ->disableOriginalConstructor()
            ->getMock();

        $event->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($request));

        return $event;
    }
}
