<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\EventDispatcher\Tests;

use Makhan\Component\DependencyInjection\Container;
use Makhan\Component\EventDispatcher\ContainerAwareEventDispatcher;
use Makhan\Component\EventDispatcher\Event;
use Makhan\Component\EventDispatcher\EventSubscriberInterface;

class ContainerAwareEventDispatcherTest extends AbstractEventDispatcherTest
{
    protected function createEventDispatcher()
    {
        $container = new Container();

        return new ContainerAwareEventDispatcher($container);
    }

    public function testAddAListenerService()
    {
        $event = new Event();

        $service = $this->getMock('Makhan\Component\EventDispatcher\Tests\Service');

        $service
            ->expects($this->once())
            ->method('onEvent')
            ->with($event)
        ;

        $container = new Container();
        $container->set('service.listener', $service);

        $dispatcher = new ContainerAwareEventDispatcher($container);
        $dispatcher->addListenerService('onEvent', array('service.listener', 'onEvent'));

        $dispatcher->dispatch('onEvent', $event);
    }

    public function testAddASubscriberService()
    {
        $event = new Event();

        $service = $this->getMock('Makhan\Component\EventDispatcher\Tests\SubscriberService');

        $service
            ->expects($this->once())
            ->method('onEvent')
            ->with($event)
        ;

        $service
            ->expects($this->once())
            ->method('onEventWithPriority')
            ->with($event)
        ;

        $service
            ->expects($this->once())
            ->method('onEventNested')
            ->with($event)
        ;

        $container = new Container();
        $container->set('service.subscriber', $service);

        $dispatcher = new ContainerAwareEventDispatcher($container);
        $dispatcher->addSubscriberService('service.subscriber', 'Makhan\Component\EventDispatcher\Tests\SubscriberService');

        $dispatcher->dispatch('onEvent', $event);
        $dispatcher->dispatch('onEventWithPriority', $event);
        $dispatcher->dispatch('onEventNested', $event);
    }

    public function testPreventDuplicateListenerService()
    {
        $event = new Event();

        $service = $this->getMock('Makhan\Component\EventDispatcher\Tests\Service');

        $service
            ->expects($this->once())
            ->method('onEvent')
            ->with($event)
        ;

        $container = new Container();
        $container->set('service.listener', $service);

        $dispatcher = new ContainerAwareEventDispatcher($container);
        $dispatcher->addListenerService('onEvent', array('service.listener', 'onEvent'), 5);
        $dispatcher->addListenerService('onEvent', array('service.listener', 'onEvent'), 10);

        $dispatcher->dispatch('onEvent', $event);
    }

    public function testHasListenersOnLazyLoad()
    {
        $event = new Event();

        $service = $this->getMock('Makhan\Component\EventDispatcher\Tests\Service');

        $container = new Container();
        $container->set('service.listener', $service);

        $dispatcher = new ContainerAwareEventDispatcher($container);
        $dispatcher->addListenerService('onEvent', array('service.listener', 'onEvent'));

        $service
            ->expects($this->once())
            ->method('onEvent')
            ->with($event)
        ;

        $this->assertTrue($dispatcher->hasListeners());

        if ($dispatcher->hasListeners('onEvent')) {
            $dispatcher->dispatch('onEvent');
        }
    }

    public function testGetListenersOnLazyLoad()
    {
        $service = $this->getMock('Makhan\Component\EventDispatcher\Tests\Service');

        $container = new Container();
        $container->set('service.listener', $service);

        $dispatcher = new ContainerAwareEventDispatcher($container);
        $dispatcher->addListenerService('onEvent', array('service.listener', 'onEvent'));

        $listeners = $dispatcher->getListeners();

        $this->assertTrue(isset($listeners['onEvent']));

        $this->assertCount(1, $dispatcher->getListeners('onEvent'));
    }

    public function testRemoveAfterDispatch()
    {
        $service = $this->getMock('Makhan\Component\EventDispatcher\Tests\Service');

        $container = new Container();
        $container->set('service.listener', $service);

        $dispatcher = new ContainerAwareEventDispatcher($container);
        $dispatcher->addListenerService('onEvent', array('service.listener', 'onEvent'));

        $dispatcher->dispatch('onEvent', new Event());
        $dispatcher->removeListener('onEvent', array($container->get('service.listener'), 'onEvent'));
        $this->assertFalse($dispatcher->hasListeners('onEvent'));
    }

    public function testRemoveBeforeDispatch()
    {
        $service = $this->getMock('Makhan\Component\EventDispatcher\Tests\Service');

        $container = new Container();
        $container->set('service.listener', $service);

        $dispatcher = new ContainerAwareEventDispatcher($container);
        $dispatcher->addListenerService('onEvent', array('service.listener', 'onEvent'));

        $dispatcher->removeListener('onEvent', array($container->get('service.listener'), 'onEvent'));
        $this->assertFalse($dispatcher->hasListeners('onEvent'));
    }
}

class Service
{
    public function onEvent(Event $e)
    {
    }
}

class SubscriberService implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'onEvent' => 'onEvent',
            'onEventWithPriority' => array('onEventWithPriority', 10),
            'onEventNested' => array(array('onEventNested')),
        );
    }

    public function onEvent(Event $e)
    {
    }

    public function onEventWithPriority(Event $e)
    {
    }

    public function onEventNested(Event $e)
    {
    }
}
