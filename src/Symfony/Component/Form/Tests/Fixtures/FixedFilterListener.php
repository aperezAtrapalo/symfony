<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Form\Tests\Fixtures;

use Makhan\Component\Form\FormEvents;
use Makhan\Component\Form\FormEvent;
use Makhan\Component\EventDispatcher\EventSubscriberInterface;

class FixedFilterListener implements EventSubscriberInterface
{
    private $mapping;

    public function __construct(array $mapping)
    {
        $this->mapping = array_merge(array(
            'preSubmit' => array(),
            'onSubmit' => array(),
            'preSetData' => array(),
        ), $mapping);
    }

    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();

        if (isset($this->mapping['preSubmit'][$data])) {
            $event->setData($this->mapping['preSubmit'][$data]);
        }
    }

    public function onSubmit(FormEvent $event)
    {
        $data = $event->getData();

        if (isset($this->mapping['onSubmit'][$data])) {
            $event->setData($this->mapping['onSubmit'][$data]);
        }
    }

    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();

        if (isset($this->mapping['preSetData'][$data])) {
            $event->setData($this->mapping['preSetData'][$data]);
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SUBMIT => 'preSubmit',
            FormEvents::SUBMIT => 'onSubmit',
            FormEvents::PRE_SET_DATA => 'preSetData',
        );
    }
}
