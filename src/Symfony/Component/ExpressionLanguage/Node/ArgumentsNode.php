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
class ArgumentsNode extends ArrayNode
{
    public function compile(Compiler $compiler)
    {
        $this->compileArguments($compiler, false);
    }
}
