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

use Makhan\Component\PropertyInfo\PropertyInfoExtractor;
use Makhan\Component\PropertyInfo\Tests\Fixtures\DummyExtractor;
use Makhan\Component\PropertyInfo\Tests\Fixtures\NullExtractor;
use Makhan\Component\PropertyInfo\Type;

/**
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class AbstractPropertyInfoExtractorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PropertyInfoExtractor
     */
    protected $propertyInfo;

    protected function setUp()
    {
        $extractors = array(new NullExtractor(), new DummyExtractor());
        $this->propertyInfo = new PropertyInfoExtractor($extractors, $extractors, $extractors, $extractors);
    }

    public function testInstanceOf()
    {
        $this->assertInstanceOf('Makhan\Component\PropertyInfo\PropertyInfoExtractorInterface', $this->propertyInfo);
        $this->assertInstanceOf('Makhan\Component\PropertyInfo\PropertyTypeExtractorInterface', $this->propertyInfo);
        $this->assertInstanceOf('Makhan\Component\PropertyInfo\PropertyDescriptionExtractorInterface', $this->propertyInfo);
        $this->assertInstanceOf('Makhan\Component\PropertyInfo\PropertyAccessExtractorInterface', $this->propertyInfo);
    }

    public function testGetShortDescription()
    {
        $this->assertSame('short', $this->propertyInfo->getShortDescription('Foo', 'bar', array()));
    }

    public function testGetLongDescription()
    {
        $this->assertSame('long', $this->propertyInfo->getLongDescription('Foo', 'bar', array()));
    }

    public function testGetTypes()
    {
        $this->assertEquals(array(new Type(Type::BUILTIN_TYPE_INT)), $this->propertyInfo->getTypes('Foo', 'bar', array()));
    }

    public function testIsReadable()
    {
        $this->assertTrue($this->propertyInfo->isReadable('Foo', 'bar', array()));
    }

    public function testIsWritable()
    {
        $this->assertTrue($this->propertyInfo->isWritable('Foo', 'bar', array()));
    }

    public function testGetProperties()
    {
        $this->assertEquals(array('a', 'b'), $this->propertyInfo->getProperties('Foo'));
    }
}
