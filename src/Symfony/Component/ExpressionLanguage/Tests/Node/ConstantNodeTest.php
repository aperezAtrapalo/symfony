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

use Makhan\Component\ExpressionLanguage\Node\ConstantNode;

class ConstantNodeTest extends AbstractNodeTest
{
    public function getEvaluateData()
    {
        return array(
            array(false, new ConstantNode(false)),
            array(true, new ConstantNode(true)),
            array(null, new ConstantNode(null)),
            array(3, new ConstantNode(3)),
            array(3.3, new ConstantNode(3.3)),
            array('foo', new ConstantNode('foo')),
            array(array(1, 'b' => 'a'), new ConstantNode(array(1, 'b' => 'a'))),
        );
    }

    public function getCompileData()
    {
        return array(
            array('false', new ConstantNode(false)),
            array('true', new ConstantNode(true)),
            array('null', new ConstantNode(null)),
            array('3', new ConstantNode(3)),
            array('3.3', new ConstantNode(3.3)),
            array('"foo"', new ConstantNode('foo')),
            array('array(0 => 1, "b" => "a")', new ConstantNode(array(1, 'b' => 'a'))),
        );
    }
}
