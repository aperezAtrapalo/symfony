<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\ExpressionLanguage\Tests\Node;

use Makhan\Component\ExpressionLanguage\Node\UnaryNode;
use Makhan\Component\ExpressionLanguage\Node\ConstantNode;

class UnaryNodeTest extends AbstractNodeTest
{
    public function getEvaluateData()
    {
        return array(
            array(-1, new UnaryNode('-', new ConstantNode(1))),
            array(3, new UnaryNode('+', new ConstantNode(3))),
            array(false, new UnaryNode('!', new ConstantNode(true))),
            array(false, new UnaryNode('not', new ConstantNode(true))),
        );
    }

    public function getCompileData()
    {
        return array(
            array('(-1)', new UnaryNode('-', new ConstantNode(1))),
            array('(+3)', new UnaryNode('+', new ConstantNode(3))),
            array('(!true)', new UnaryNode('!', new ConstantNode(true))),
            array('(!true)', new UnaryNode('not', new ConstantNode(true))),
        );
    }
}
