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

use Makhan\Component\PropertyAccess\PropertyAccessorBuilder;

class PropertyAccessorBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PropertyAccessorBuilder
     */
    protected $builder;

    protected function setUp()
    {
        $this->builder = new PropertyAccessorBuilder();
    }

    protected function tearDown()
    {
        $this->builder = null;
    }

    public function testEnableMagicCall()
    {
        $this->assertSame($this->builder, $this->builder->enableMagicCall());
    }

    public function testDisableMagicCall()
    {
        $this->assertSame($this->builder, $this->builder->disableMagicCall());
    }

    public function testIsMagicCallEnable()
    {
        $this->assertFalse($this->builder->isMagicCallEnabled());
        $this->assertTrue($this->builder->enableMagicCall()->isMagicCallEnabled());
        $this->assertFalse($this->builder->disableMagicCall()->isMagicCallEnabled());
    }

    public function testGetPropertyAccessor()
    {
        $this->assertInstanceOf('Makhan\Component\PropertyAccess\PropertyAccessor', $this->builder->getPropertyAccessor());
        $this->assertInstanceOf('Makhan\Component\PropertyAccess\PropertyAccessor', $this->builder->enableMagicCall()->getPropertyAccessor());
    }
}
