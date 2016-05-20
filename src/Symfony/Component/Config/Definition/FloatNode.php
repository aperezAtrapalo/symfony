<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Config\Definition;

use Makhan\Component\Config\Definition\Exception\InvalidTypeException;

/**
 * This node represents a float value in the config tree.
 *
 * @author Jeanmonod David <david.jeanmonod@gmail.com>
 */
class FloatNode extends NumericNode
{
    /**
     * {@inheritdoc}
     */
    protected function validateType($value)
    {
        // Integers are also accepted, we just cast them
        if (is_int($value)) {
            $value = (float) $value;
        }

        if (!is_float($value)) {
            $ex = new InvalidTypeException(sprintf('Invalid type for path "%s". Expected float, but got %s.', $this->getPath(), gettype($value)));
            if ($hint = $this->getInfo()) {
                $ex->addHint($hint);
            }
            $ex->setPath($this->getPath());

            throw $ex;
        }
    }
}
