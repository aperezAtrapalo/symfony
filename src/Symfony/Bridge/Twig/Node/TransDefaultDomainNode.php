<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bridge\Twig\Node;

/**
 * @author Fabien Potencier <fabien@makhan.com>
 */
class TransDefaultDomainNode extends \Twig_Node
{
    public function __construct(\Twig_Node_Expression $expr, $lineno = 0, $tag = null)
    {
        parent::__construct(array('expr' => $expr), array(), $lineno, $tag);
    }

    /**
     * Compiles the node to PHP.
     *
     * @param \Twig_Compiler $compiler A Twig_Compiler instance
     */
    public function compile(\Twig_Compiler $compiler)
    {
        // noop as this node is just a marker for TranslationDefaultDomainNodeVisitor
    }
}
