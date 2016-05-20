<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\ExpressionLanguage\Node;

use Makhan\Component\ExpressionLanguage\Compiler;

/**
 * @author Fabien Potencier <fabien@makhan.com>
 *
 * @internal
 */
class ConstantNode extends Node
{
    public function __construct($value)
    {
        parent::__construct(
            array(),
            array('value' => $value)
        );
    }

    public function compile(Compiler $compiler)
    {
        $compiler->repr($this->attributes['value']);
    }

    public function evaluate($functions, $values)
    {
        return $this->attributes['value'];
    }
}
