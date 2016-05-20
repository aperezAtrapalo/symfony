<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Form\Extension\Validator\Type;

use Makhan\Component\Form\AbstractTypeExtension;
use Makhan\Component\OptionsResolver\Options;
use Makhan\Component\OptionsResolver\OptionsResolver;

/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class RepeatedTypeValidatorExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        // Map errors to the first field
        $errorMapping = function (Options $options) {
            return array('.' => $options['first_name']);
        };

        $resolver->setDefaults(array(
            'error_mapping' => $errorMapping,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return 'Makhan\Component\Form\Extension\Core\Type\RepeatedType';
    }
}
