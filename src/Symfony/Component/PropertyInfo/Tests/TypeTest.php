<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\PropertyInfo\Tests;

use Makhan\Component\PropertyInfo\Type;

/**
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class TypeTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $type = new Type('object', true, 'ArrayObject', true, new Type('int'), new Type('string'));

        $this->assertEquals(Type::BUILTIN_TYPE_OBJECT, $type->getBuiltinType());
        $this->assertTrue($type->isNullable());
        $this->assertEquals('ArrayObject', $type->getClassName());
        $this->assertTrue($type->isCollection());

        $collectionKeyType = $type->getCollectionKeyType();
        $this->assertInstanceOf('Makhan\Component\PropertyInfo\Type', $collectionKeyType);
        $this->assertEquals(Type::BUILTIN_TYPE_INT, $collectionKeyType->getBuiltinType());

        $collectionValueType = $type->getCollectionValueType();
        $this->assertInstanceOf('Makhan\Component\PropertyInfo\Type', $collectionValueType);
        $this->assertEquals(Type::BUILTIN_TYPE_STRING, $collectionValueType->getBuiltinType());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage "foo" is not a valid PHP type.
     */
    public function testInvalidType()
    {
        new Type('foo');
    }
}
