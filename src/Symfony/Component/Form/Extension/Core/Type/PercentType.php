<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Form\Extension\Core\Type;

use Makhan\Component\Form\AbstractType;
use Makhan\Component\Form\FormBuilderInterface;
use Makhan\Component\Form\Extension\Core\DataTransformer\PercentToLocalizedStringTransformer;
use Makhan\Component\OptionsResolver\OptionsResolver;

class PercentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer(new PercentToLocalizedStringTransformer($options['scale'], $options['type']));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'scale' => 0,
            'type' => 'fractional',
            'compound' => false,
        ));

        $resolver->setAllowedValues('type', array(
            'fractional',
            'integer',
        ));

        $resolver->setAllowedTypes('scale', 'int');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'percent';
    }
}
