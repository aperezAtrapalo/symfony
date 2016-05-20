<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Form\Tests;

use Makhan\Component\Form\FormRegistry;
use Makhan\Component\Form\FormTypeGuesserChain;
use Makhan\Component\Form\ResolvedFormType;
use Makhan\Component\Form\ResolvedFormTypeFactoryInterface;
use Makhan\Component\Form\Tests\Fixtures\FooSubType;
use Makhan\Component\Form\Tests\Fixtures\FooType;
use Makhan\Component\Form\Tests\Fixtures\FooTypeBarExtension;
use Makhan\Component\Form\Tests\Fixtures\FooTypeBazExtension;
use Makhan\Component\Form\Tests\Fixtures\TestExtension;

/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class FormRegistryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FormRegistry
     */
    private $registry;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ResolvedFormTypeFactoryInterface
     */
    private $resolvedTypeFactory;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $guesser1;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $guesser2;

    /**
     * @var TestExtension
     */
    private $extension1;

    /**
     * @var TestExtension
     */
    private $extension2;

    protected function setUp()
    {
        $this->resolvedTypeFactory = $this->getMock('Makhan\Component\Form\ResolvedFormTypeFactory');
        $this->guesser1 = $this->getMock('Makhan\Component\Form\FormTypeGuesserInterface');
        $this->guesser2 = $this->getMock('Makhan\Component\Form\FormTypeGuesserInterface');
        $this->extension1 = new TestExtension($this->guesser1);
        $this->extension2 = new TestExtension($this->guesser2);
        $this->registry = new FormRegistry(array(
            $this->extension1,
            $this->extension2,
        ), $this->resolvedTypeFactory);
    }

    public function testGetTypeFromExtension()
    {
        $type = new FooType();
        $resolvedType = new ResolvedFormType($type);

        $this->extension2->addType($type);

        $this->resolvedTypeFactory->expects($this->once())
            ->method('createResolvedType')
            ->with($type)
            ->willReturn($resolvedType);

        $this->assertSame($resolvedType, $this->registry->getType(get_class($type)));
    }

    public function testLoadUnregisteredType()
    {
        $type = new FooType();
        $resolvedType = new ResolvedFormType($type);

        $this->resolvedTypeFactory->expects($this->once())
            ->method('createResolvedType')
            ->with($type)
            ->willReturn($resolvedType);

        $this->assertSame($resolvedType, $this->registry->getType('Makhan\Component\Form\Tests\Fixtures\FooType'));
    }

    /**
     * @expectedException \Makhan\Component\Form\Exception\InvalidArgumentException
     */
    public function testFailIfUnregisteredTypeNoClass()
    {
        $this->registry->getType('Makhan\Blubb');
    }

    /**
     * @expectedException \Makhan\Component\Form\Exception\InvalidArgumentException
     */
    public function testFailIfUnregisteredTypeNoFormType()
    {
        $this->registry->getType('stdClass');
    }

    public function testGetTypeWithTypeExtensions()
    {
        $type = new FooType();
        $ext1 = new FooTypeBarExtension();
        $ext2 = new FooTypeBazExtension();
        $resolvedType = new ResolvedFormType($type, array($ext1, $ext2));

        $this->extension2->addType($type);
        $this->extension1->addTypeExtension($ext1);
        $this->extension2->addTypeExtension($ext2);

        $this->resolvedTypeFactory->expects($this->once())
            ->method('createResolvedType')
            ->with($type, array($ext1, $ext2))
            ->willReturn($resolvedType);

        $this->assertSame($resolvedType, $this->registry->getType(get_class($type)));
    }

    public function testGetTypeConnectsParent()
    {
        $parentType = new FooType();
        $type = new FooSubType();
        $parentResolvedType = new ResolvedFormType($parentType);
        $resolvedType = new ResolvedFormType($type);

        $this->extension1->addType($parentType);
        $this->extension2->addType($type);

        $this->resolvedTypeFactory->expects($this->at(0))
            ->method('createResolvedType')
            ->with($parentType)
            ->willReturn($parentResolvedType);

        $this->resolvedTypeFactory->expects($this->at(1))
            ->method('createResolvedType')
            ->with($type, array(), $parentResolvedType)
            ->willReturn($resolvedType);

        $this->assertSame($resolvedType, $this->registry->getType(get_class($type)));
    }

    /**
     * @expectedException \Makhan\Component\Form\Exception\InvalidArgumentException
     */
    public function testGetTypeThrowsExceptionIfTypeNotFound()
    {
        $this->registry->getType('bar');
    }

    public function testHasTypeAfterLoadingFromExtension()
    {
        $type = new FooType();
        $resolvedType = new ResolvedFormType($type);

        $this->resolvedTypeFactory->expects($this->once())
            ->method('createResolvedType')
            ->with($type)
            ->willReturn($resolvedType);

        $this->extension2->addType($type);

        $this->assertTrue($this->registry->hasType(get_class($type)));
    }

    public function testHasTypeIfFQCN()
    {
        $this->assertTrue($this->registry->hasType('Makhan\Component\Form\Tests\Fixtures\FooType'));
    }

    public function testDoesNotHaveTypeIfNonExistingClass()
    {
        $this->assertFalse($this->registry->hasType('Makhan\Blubb'));
    }

    public function testDoesNotHaveTypeIfNoFormType()
    {
        $this->assertFalse($this->registry->hasType('stdClass'));
    }

    public function testGetTypeGuesser()
    {
        $expectedGuesser = new FormTypeGuesserChain(array($this->guesser1, $this->guesser2));

        $this->assertEquals($expectedGuesser, $this->registry->getTypeGuesser());

        $registry = new FormRegistry(
            array($this->getMock('Makhan\Component\Form\FormExtensionInterface')),
            $this->resolvedTypeFactory
        );

        $this->assertNull($registry->getTypeGuesser());
    }

    public function testGetExtensions()
    {
        $expectedExtensions = array($this->extension1, $this->extension2);

        $this->assertEquals($expectedExtensions, $this->registry->getExtensions());
    }
}
