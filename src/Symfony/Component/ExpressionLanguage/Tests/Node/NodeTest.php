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

use Makhan\Component\ExpressionLanguage\Node\Node;
use Makhan\Component\ExpressionLanguage\Node\ConstantNode;

class NodeTest extends \PHPUnit_Framework_TestCase
{
    public function testToString()
    {
        $node = new Node(array(new ConstantNode('foo')));

        $this->assertEquals(<<<EOF
Node(
    ConstantNode(value: 'foo')
)
EOF
        , (string) $node);
    }

    public function testSerialization()
    {
        $node = new Node(array('foo' => 'bar'), array('bar' => 'foo'));

        $serializedNode = serialize($node);
        $unserializedNode = unserialize($serializedNode);

        $this->assertEquals($node, $unserializedNode);
    }
}
