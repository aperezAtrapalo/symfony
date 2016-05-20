<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\ExpressionLanguage;

use Makhan\Component\ExpressionLanguage\Node\Node;

/**
 * Represents an already parsed expression.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 */
class ParsedExpression extends Expression
{
    private $nodes;

    /**
     * Constructor.
     *
     * @param string $expression An expression
     * @param Node   $nodes      A Node representing the expression
     */
    public function __construct($expression, Node $nodes)
    {
        parent::__construct($expression);

        $this->nodes = $nodes;
    }

    public function getNodes()
    {
        return $this->nodes;
    }
}
