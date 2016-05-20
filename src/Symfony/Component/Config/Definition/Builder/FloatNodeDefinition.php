<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Config\Definition\Builder;

use Makhan\Component\Config\Definition\FloatNode;

/**
 * This class provides a fluent interface for defining a float node.
 *
 * @author Jeanmonod David <david.jeanmonod@gmail.com>
 */
class FloatNodeDefinition extends NumericNodeDefinition
{
    /**
     * Instantiates a Node.
     *
     * @return FloatNode The node
     */
    protected function instantiateNode()
    {
        return new FloatNode($this->name, $this->parent, $this->min, $this->max);
    }
}
