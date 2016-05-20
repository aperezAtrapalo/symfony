<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Form\Extension\Core\EventListener;

use Makhan\Component\Form\FormEvents;
use Makhan\Component\Form\FormEvent;
use Makhan\Component\EventDispatcher\EventSubscriberInterface;
use Makhan\Component\Form\Util\StringUtil;

/**
 * Trims string data.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class TrimListener implements EventSubscriberInterface
{
    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();

        if (!is_string($data)) {
            return;
        }

        $event->setData(StringUtil::trim($data));
    }

    public static function getSubscribedEvents()
    {
        return array(FormEvents::PRE_SUBMIT => 'preSubmit');
    }
}
