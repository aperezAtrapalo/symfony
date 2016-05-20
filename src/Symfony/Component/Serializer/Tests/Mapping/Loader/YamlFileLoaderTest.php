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

use Makhan\Component\Serializer\Mapping\Loader\YamlFileLoader;
use Makhan\Component\Serializer\Mapping\ClassMetadata;
use Makhan\Component\Serializer\Tests\Mapping\TestClassMetadataFactory;

/**
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class YamlFileLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var YamlFileLoader
     */
    private $loader;
    /**
     * @var ClassMetadata
     */
    private $metadata;

    protected function setUp()
    {
        $this->loader = new YamlFileLoader(__DIR__.'/../../Fixtures/serialization.yml');
        $this->metadata = new ClassMetadata('Makhan\Component\Serializer\Tests\Fixtures\GroupDummy');
    }

    public function testInterface()
    {
        $this->assertInstanceOf('Makhan\Component\Serializer\Mapping\Loader\LoaderInterface', $this->loader);
    }

    public function testLoadClassMetadataReturnsTrueIfSuccessful()
    {
        $this->assertTrue($this->loader->loadClassMetadata($this->metadata));
    }

    public function testLoadClassMetadataReturnsFalseWhenEmpty()
    {
        $loader = new YamlFileLoader(__DIR__.'/../../Fixtures/empty-mapping.yml');
        $this->assertFalse($loader->loadClassMetadata($this->metadata));
    }

    /**
     * @expectedException \Makhan\Component\Serializer\Exception\MappingException
     */
    public function testLoadClassMetadataReturnsThrowsInvalidMapping()
    {
        $loader = new YamlFileLoader(__DIR__.'/../../Fixtures/invalid-mapping.yml');
        $loader->loadClassMetadata($this->metadata);
    }

    public function testLoadClassMetadata()
    {
        $this->loader->loadClassMetadata($this->metadata);

        $this->assertEquals(TestClassMetadataFactory::createXmlCLassMetadata(), $this->metadata);
    }

    public function testMaxDepth()
    {
        $classMetadata = new ClassMetadata('Makhan\Component\Serializer\Tests\Fixtures\MaxDepthDummy');
        $this->loader->loadClassMetadata($classMetadata);

        $attributesMetadata = $classMetadata->getAttributesMetadata();
        $this->assertEquals(2, $attributesMetadata['foo']->getMaxDepth());
        $this->assertEquals(3, $attributesMetadata['bar']->getMaxDepth());
    }
}
