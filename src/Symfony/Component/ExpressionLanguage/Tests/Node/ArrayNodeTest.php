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

use Makhan\Component\ExpressionLanguage\Node\ArrayNode;
use Makhan\Component\ExpressionLanguage\Node\ConstantNode;

class ArrayNodeTest extends AbstractNodeTest
{
    public function testSerialization()
    {
        $node = $this->createArrayNode();
        $node->addElement(new ConstantNode('foo'));

        $serializedNode = serialize($node);
        $unserializedNode = unserialize($serializedNode);

        $this->assertEquals($node, $unserializedNode);
        $this->assertNotEquals($this->createArrayNode(), $unserializedNode);
    }

    public function getEvaluateData()
    {
        return array(
            array(array('b' => 'a', 'b'), $this->getArrayNode()),
        );
    }

    public function getCompileData()
    {
        return array(
            array('array("b" => "a", 0 => "b")', $this->getArrayNode()),
        );
    }

    protected function getArrayNode()
    {
        $array = $this->createArrayNode();
        $array->addElement(new ConstantNode('a'), new ConstantNode('b'));
        $array->addElement(new ConstantNode('b'));

        return $array;
    }

    protected function createArrayNode()
    {
        return new ArrayNode();
    }
}
