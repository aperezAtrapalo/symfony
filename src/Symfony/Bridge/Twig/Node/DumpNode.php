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
 * @author Julien Galenski <julien.galenski@gmail.com>
 */
class DumpNode extends \Twig_Node
{
    private $varPrefix;

    public function __construct($varPrefix, \Twig_Node $values = null, $lineno, $tag = null)
    {
        parent::__construct(array('values' => $values), array(), $lineno, $tag);
        $this->varPrefix = $varPrefix;
    }

    /**
     * {@inheritdoc}
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler
            ->write("if (\$this->env->isDebug()) {\n")
            ->indent();

        $values = $this->getNode('values');

        if (null === $values) {
            // remove embedded templates (macros) from the context
            $compiler
                ->write(sprintf('$%svars = array();'."\n", $this->varPrefix))
                ->write(sprintf('foreach ($context as $%1$skey => $%1$sval) {'."\n", $this->varPrefix))
                ->indent()
                ->write(sprintf('if (!$%sval instanceof \Twig_Template) {'."\n", $this->varPrefix))
                ->indent()
                ->write(sprintf('$%1$svars[$%1$skey] = $%1$sval;'."\n", $this->varPrefix))
                ->outdent()
                ->write("}\n")
                ->outdent()
                ->write("}\n")
                ->addDebugInfo($this)
                ->write(sprintf('\Makhan\Component\VarDumper\VarDumper::dump($%svars);'."\n", $this->varPrefix));
        } elseif (1 === $values->count()) {
            $compiler
                ->addDebugInfo($this)
                ->write('\Makhan\Component\VarDumper\VarDumper::dump(')
                ->subcompile($values->getNode(0))
                ->raw(");\n");
        } else {
            $compiler
                ->addDebugInfo($this)
                ->write('\Makhan\Component\VarDumper\VarDumper::dump(array('."\n")
                ->indent();
            foreach ($values as $node) {
                $compiler->addIndentation();
                if ($node->hasAttribute('name')) {
                    $compiler
                        ->string($node->getAttribute('name'))
                        ->raw(' => ');
                }
                $compiler
                    ->subcompile($node)
                    ->raw(",\n");
            }
            $compiler
                ->outdent()
                ->write("));\n");
        }

        $compiler
            ->outdent()
            ->write("}\n");
    }
}
