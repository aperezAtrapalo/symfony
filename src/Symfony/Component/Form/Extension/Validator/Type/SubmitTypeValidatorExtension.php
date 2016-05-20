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

/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class SubmitTypeValidatorExtension extends BaseValidatorExtension
{
    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return 'Makhan\Component\Form\Extension\Core\Type\SubmitType';
    }
}
