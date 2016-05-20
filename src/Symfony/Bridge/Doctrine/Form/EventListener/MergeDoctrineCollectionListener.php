<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bridge\Doctrine\Form\EventListener;

use Doctrine\Common\Collections\Collection;
use Makhan\Component\Form\FormEvents;
use Makhan\Component\Form\FormEvent;
use Makhan\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Merge changes from the request to a Doctrine\Common\Collections\Collection instance.
 *
 * This works with ORM, MongoDB and CouchDB instances of the collection interface.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 *
 * @see Collection
 */
class MergeDoctrineCollectionListener implements EventSubscriberInterface
{
    // Keep BC. To be removed in 4.0
    private $bc = true;
    private $bcLayer = false;

    public static function getSubscribedEvents()
    {
        // Higher priority than core MergeCollectionListener so that this one
        // is called before
        return array(
            FormEvents::SUBMIT => array(
                array('onBind', 10), // deprecated
                array('onSubmit', 5),
            ),
        );
    }

    public function onSubmit(FormEvent $event)
    {
        if ($this->bc) {
            // onBind() has been overridden from a child class
            @trigger_error('The onBind() method is deprecated since version 3.1 and will be removed in 4.0. Use the onSubmit() method instead.', E_USER_DEPRECATED);

            if (!$this->bcLayer) {
                // If parent::onBind() has not been called, then logic has been executed
                return;
            }
        }

        $collection = $event->getForm()->getData();
        $data = $event->getData();

        // If all items were removed, call clear which has a higher
        // performance on persistent collections
        if ($collection instanceof Collection && count($data) === 0) {
            $collection->clear();
        }
    }

    /**
     * Alias of {@link onSubmit()}.
     *
     * @deprecated since version 3.1, to be removed in 4.0.
     *             Use {@link onSubmit()} instead.
     */
    public function onBind(FormEvent $event)
    {
        if (__CLASS__ === get_class($this)) {
            $this->bc = false;
        } else {
            // parent::onBind() has been called
            $this->bcLayer = true;
        }
    }
}
