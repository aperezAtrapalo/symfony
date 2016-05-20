<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Form\Extension\DataCollector\Type;

use Makhan\Component\Form\AbstractTypeExtension;
use Makhan\Component\Form\Extension\DataCollector\EventListener\DataCollectorListener;
use Makhan\Component\Form\Extension\DataCollector\FormDataCollectorInterface;
use Makhan\Component\Form\FormBuilderInterface;

/**
 * Type extension for collecting data of a form with this type.
 *
 * @since  2.4
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class DataCollectorTypeExtension extends AbstractTypeExtension
{
    /**
     * @var \Makhan\Component\EventDispatcher\EventSubscriberInterface
     */
    private $listener;

    public function __construct(FormDataCollectorInterface $dataCollector)
    {
        $this->listener = new DataCollectorListener($dataCollector);
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventSubscriber($this->listener);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return 'Makhan\Component\Form\Extension\Core\Type\FormType';
    }
}
