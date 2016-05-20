<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Validator\Constraints;

/**
 * Validates values are equal (==).
 *
 * @author Daniel Holmes <daniel@danielholmes.org>
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class EqualToValidator extends AbstractComparisonValidator
{
    /**
     * {@inheritdoc}
     */
    protected function compareValues($value1, $value2)
    {
        return $value1 == $value2;
    }

    /**
     * {@inheritdoc}
     */
    protected function getErrorCode()
    {
        return EqualTo::NOT_EQUAL_ERROR;
    }
}
