<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Serializer\Tests\Mapping\Loader;

use Doctrine\Common\Annotations\AnnotationReader;
use Makhan\Component\Serializer\Mapping\ClassMetadata;
use Makhan\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Makhan\Component\Serializer\Tests\Mapping\TestClassMetadataFactory;

/**
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class AnnotationLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AnnotationLoader
     */
    private $loader;

    protected function setUp()
    {
        $this->loader = new AnnotationLoader(new AnnotationReader());
    }

    public function testInterface()
    {
        $this->assertInstanceOf('Makhan\Component\Serializer\Mapping\Loader\LoaderInterface', $this->loader);
    }

    public function testLoadClassMetadataReturnsTrueIfSuccessful()
    {
        $classMetadata = new ClassMetadata('Makhan\Component\Serializer\Tests\Fixtures\GroupDummy');

        $this->assertTrue($this->loader->loadClassMetadata($classMetadata));
    }

    public function testLoadGroups()
    {
        $classMetadata = new ClassMetadata('Makhan\Component\Serializer\Tests\Fixtures\GroupDummy');
        $this->loader->loadClassMetadata($classMetadata);

        $this->assertEquals(TestClassMetadataFactory::createClassMetadata(), $classMetadata);
    }

    public function testLoadMaxDepth()
    {
        $classMetadata = new ClassMetadata('Makhan\Component\Serializer\Tests\Fixtures\MaxDepthDummy');
        $this->loader->loadClassMetadata($classMetadata);

        $attributesMetadata = $classMetadata->getAttributesMetadata();
        $this->assertEquals(2, $attributesMetadata['foo']->getMaxDepth());
        $this->assertEquals(3, $attributesMetadata['bar']->getMaxDepth());
    }

    public function testLoadClassMetadataAndMerge()
    {
        $classMetadata = new ClassMetadata('Makhan\Component\Serializer\Tests\Fixtures\GroupDummy');
        $parentClassMetadata = new ClassMetadata('Makhan\Component\Serializer\Tests\Fixtures\GroupDummyParent');

        $this->loader->loadClassMetadata($parentClassMetadata);
        $classMetadata->merge($parentClassMetadata);

        $this->loader->loadClassMetadata($classMetadata);

        $this->assertEquals(TestClassMetadataFactory::createClassMetadata(true), $classMetadata);
    }
}
