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
class NameNode extends Node
{
    public function __construct($name)
    {
        parent::__construct(
            array(),
            array('name' => $name)
        );
    }

    public function compile(Compiler $compiler)
    {
        $compiler->raw('$'.$this->attributes['name']);
    }

    public function evaluate($functions, $values)
    {
        return $values[$this->attributes['name']];
    }
}
