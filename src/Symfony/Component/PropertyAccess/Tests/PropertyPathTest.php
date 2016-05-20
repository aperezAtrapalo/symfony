<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\PropertyAccess\Tests;

use Makhan\Component\PropertyAccess\PropertyPath;

class PropertyPathTest extends \PHPUnit_Framework_TestCase
{
    public function testToString()
    {
        $path = new PropertyPath('reference.traversable[index].property');

        $this->assertEquals('reference.traversable[index].property', $path->__toString());
    }

    /**
     * @expectedException \Makhan\Component\PropertyAccess\Exception\InvalidPropertyPathException
     */
    public function testDotIsRequiredBeforeProperty()
    {
        new PropertyPath('[index]property');
    }

    /**
     * @expectedException \Makhan\Component\PropertyAccess\Exception\InvalidPropertyPathException
     */
    public function testDotCannotBePresentAtTheBeginning()
    {
        new PropertyPath('.property');
    }

    public function providePathsContainingUnexpectedCharacters()
    {
        return array(
            array('property.'),
            array('property.['),
            array('property..'),
            array('property['),
            array('property[['),
            array('property[.'),
            array('property[]'),
        );
    }

    /**
     * @dataProvider providePathsContainingUnexpectedCharacters
     * @expectedException \Makhan\Component\PropertyAccess\Exception\InvalidPropertyPathException
     */
    public function testUnexpectedCharacters($path)
    {
        new PropertyPath($path);
    }

    /**
     * @expectedException \Makhan\Component\PropertyAccess\Exception\InvalidPropertyPathException
     */
    public function testPathCannotBeEmpty()
    {
        new PropertyPath('');
    }

    /**
     * @expectedException \Makhan\Component\PropertyAccess\Exception\InvalidArgumentException
     */
    public function testPathCannotBeNull()
    {
        new PropertyPath(null);
    }

    /**
     * @expectedException \Makhan\Component\PropertyAccess\Exception\InvalidArgumentException
     */
    public function testPathCannotBeFalse()
    {
        new PropertyPath(false);
    }

    public function testZeroIsValidPropertyPath()
    {
        new PropertyPath('0');
    }

    public function testGetParentWithDot()
    {
        $propertyPath = new PropertyPath('grandpa.parent.child');

        $this->assertEquals(new PropertyPath('grandpa.parent'), $propertyPath->getParent());
    }

    public function testGetParentWithIndex()
    {
        $propertyPath = new PropertyPath('grandpa.parent[child]');

        $this->assertEquals(new PropertyPath('grandpa.parent'), $propertyPath->getParent());
    }

    public function testGetParentWhenThereIsNoParent()
    {
        $propertyPath = new PropertyPath('path');

        $this->assertNull($propertyPath->getParent());
    }

    public function testCopyConstructor()
    {
        $propertyPath = new PropertyPath('grandpa.parent[child]');
        $copy = new PropertyPath($propertyPath);

        $this->assertEquals($propertyPath, $copy);
    }

    public function testGetElement()
    {
        $propertyPath = new PropertyPath('grandpa.parent[child]');

        $this->assertEquals('child', $propertyPath->getElement(2));
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testGetElementDoesNotAcceptInvalidIndices()
    {
        $propertyPath = new PropertyPath('grandpa.parent[child]');

        $propertyPath->getElement(3);
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testGetElementDoesNotAcceptNegativeIndices()
    {
        $propertyPath = new PropertyPath('grandpa.parent[child]');

        $propertyPath->getElement(-1);
    }

    public function testIsProperty()
    {
        $propertyPath = new PropertyPath('grandpa.parent[child]');

        $this->assertTrue($propertyPath->isProperty(1));
        $this->assertFalse($propertyPath->isProperty(2));
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testIsPropertyDoesNotAcceptInvalidIndices()
    {
        $propertyPath = new PropertyPath('grandpa.parent[child]');

        $propertyPath->isProperty(3);
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testIsPropertyDoesNotAcceptNegativeIndices()
    {
        $propertyPath = new PropertyPath('grandpa.parent[child]');

        $propertyPath->isProperty(-1);
    }

    public function testIsIndex()
    {
        $propertyPath = new PropertyPath('grandpa.parent[child]');

        $this->assertFalse($propertyPath->isIndex(1));
        $this->assertTrue($propertyPath->isIndex(2));
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testIsIndexDoesNotAcceptInvalidIndices()
    {
        $propertyPath = new PropertyPath('grandpa.parent[child]');

        $propertyPath->isIndex(3);
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testIsIndexDoesNotAcceptNegativeIndices()
    {
        $propertyPath = new PropertyPath('grandpa.parent[child]');

        $propertyPath->isIndex(-1);
    }
}
