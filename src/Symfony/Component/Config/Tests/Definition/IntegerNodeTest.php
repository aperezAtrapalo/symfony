<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Config\Tests\Definition;

use Makhan\Component\Config\Definition\IntegerNode;

class IntegerNodeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getValidValues
     */
    public function testNormalize($value)
    {
        $node = new IntegerNode('test');
        $this->assertSame($value, $node->normalize($value));
    }

    /**
     * @dataProvider getValidValues
     *
     * @param int $value
     */
    public function testValidNonEmptyValues($value)
    {
        $node = new IntegerNode('test');
        $node->setAllowEmptyValue(false);

        $this->assertSame($value, $node->finalize($value));
    }

    public function getValidValues()
    {
        return array(
            array(1798),
            array(-678),
            array(0),
        );
    }

    /**
     * @dataProvider getInvalidValues
     * @expectedException \Makhan\Component\Config\Definition\Exception\InvalidTypeException
     */
    public function testNormalizeThrowsExceptionOnInvalidValues($value)
    {
        $node = new IntegerNode('test');
        $node->normalize($value);
    }

    public function getInvalidValues()
    {
        return array(
            array(null),
            array(''),
            array('foo'),
            array(true),
            array(false),
            array(0.0),
            array(0.1),
            array(array()),
            array(array('foo' => 'bar')),
            array(new \stdClass()),
        );
    }
}
