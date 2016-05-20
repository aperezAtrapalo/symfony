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
use Makhan\Component\Form\ButtonTypeInterface;

/**
 * A reset button.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class ResetType extends AbstractType implements ButtonTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return __NAMESPACE__.'\ButtonType';
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'reset';
    }
}
