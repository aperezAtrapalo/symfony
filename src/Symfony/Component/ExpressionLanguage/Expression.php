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

/**
 * Represents an expression.
 *
 * @author Fabien Potencier <fabien@makhan.com>
 */
class Expression
{
    protected $expression;

    /**
     * Constructor.
     *
     * @param string $expression An expression
     */
    public function __construct($expression)
    {
        $this->expression = (string) $expression;
    }

    /**
     * Gets the expression.
     *
     * @return string The expression
     */
    public function __toString()
    {
        return $this->expression;
    }
}
