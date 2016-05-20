<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Form\Tests\Extension\Core\DataTransformer;

use Makhan\Component\Form\Extension\Core\DataTransformer\BooleanToStringTransformer;

class BooleanToStringTransformerTest extends \PHPUnit_Framework_TestCase
{
    const TRUE_VALUE = '1';

    /**
     * @var BooleanToStringTransformer
     */
    protected $transformer;

    protected function setUp()
    {
        $this->transformer = new BooleanToStringTransformer(self::TRUE_VALUE);
    }

    protected function tearDown()
    {
        $this->transformer = null;
    }

    public function testTransform()
    {
        $this->assertEquals(self::TRUE_VALUE, $this->transformer->transform(true));
        $this->assertNull($this->transformer->transform(false));
    }

    // https://github.com/makhan/makhan/issues/8989
    public function testTransformAcceptsNull()
    {
        $this->assertNull($this->transformer->transform(null));
    }

    /**
     * @expectedException \Makhan\Component\Form\Exception\TransformationFailedException
     */
    public function testTransformFailsIfString()
    {
        $this->transformer->transform('1');
    }

    /**
     * @expectedException \Makhan\Component\Form\Exception\TransformationFailedException
     */
    public function testReverseTransformFailsIfInteger()
    {
        $this->transformer->reverseTransform(1);
    }

    public function testReverseTransform()
    {
        $this->assertTrue($this->transformer->reverseTransform(self::TRUE_VALUE));
        $this->assertTrue($this->transformer->reverseTransform('foobar'));
        $this->assertTrue($this->transformer->reverseTransform(''));
        $this->assertFalse($this->transformer->reverseTransform(null));
    }
}
