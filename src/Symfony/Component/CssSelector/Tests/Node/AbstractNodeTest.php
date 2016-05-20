<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\CssSelector\Tests\Node;

use Makhan\Component\CssSelector\Node\NodeInterface;

abstract class AbstractNodeTest extends \PHPUnit_Framework_TestCase
{
    /** @dataProvider getToStringConversionTestData */
    public function testToStringConversion(NodeInterface $node, $representation)
    {
        $this->assertEquals($representation, (string) $node);
    }

    /** @dataProvider getSpecificityValueTestData */
    public function testSpecificityValue(NodeInterface $node, $value)
    {
        $this->assertEquals($value, $node->getSpecificity()->getValue());
    }

    abstract public function getToStringConversionTestData();

    abstract public function getSpecificityValueTestData();
}
