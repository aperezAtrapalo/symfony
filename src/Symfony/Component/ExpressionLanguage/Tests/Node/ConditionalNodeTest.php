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

use Makhan\Component\ExpressionLanguage\Node\ConditionalNode;
use Makhan\Component\ExpressionLanguage\Node\ConstantNode;

class ConditionalNodeTest extends AbstractNodeTest
{
    public function getEvaluateData()
    {
        return array(
            array(1, new ConditionalNode(new ConstantNode(true), new ConstantNode(1), new ConstantNode(2))),
            array(2, new ConditionalNode(new ConstantNode(false), new ConstantNode(1), new ConstantNode(2))),
        );
    }

    public function getCompileData()
    {
        return array(
            array('((true) ? (1) : (2))', new ConditionalNode(new ConstantNode(true), new ConstantNode(1), new ConstantNode(2))),
            array('((false) ? (1) : (2))', new ConditionalNode(new ConstantNode(false), new ConstantNode(1), new ConstantNode(2))),
        );
    }
}
