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
class FunctionNode extends Node
{
    public function __construct($name, Node $arguments)
    {
        parent::__construct(
            array('arguments' => $arguments),
            array('name' => $name)
        );
    }

    public function compile(Compiler $compiler)
    {
        $arguments = array();
        foreach ($this->nodes['arguments']->nodes as $node) {
            $arguments[] = $compiler->subcompile($node);
        }

        $function = $compiler->getFunction($this->attributes['name']);

        $compiler->raw(call_user_func_array($function['compiler'], $arguments));
    }

    public function evaluate($functions, $values)
    {
        $arguments = array($values);
        foreach ($this->nodes['arguments']->nodes as $node) {
            $arguments[] = $node->evaluate($functions, $values);
        }

        return call_user_func_array($functions[$this->attributes['name']]['evaluator'], $arguments);
    }
}
