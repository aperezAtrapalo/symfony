<?php

namespace Makhan\Component\Serializer\Tests\Normalizer;

use Makhan\Component\Serializer\Mapping\AttributeMetadata;
use Makhan\Component\Serializer\Mapping\ClassMetadata;
use Makhan\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Makhan\Component\Serializer\Normalizer\AbstractNormalizer;
use Makhan\Component\Serializer\Normalizer\ObjectNormalizer;
use Makhan\Component\Serializer\Tests\Fixtures\AbstractNormalizerDummy;
use Makhan\Component\Serializer\Tests\Fixtures\ProxyDummy;

/**
 * Provides a dummy Normalizer which extends the AbstractNormalizer.
 *
 * @author Konstantin S. M. Möllers <ksm.moellers@gmail.com>
 */
class AbstractNormalizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AbstractNormalizerDummy
     */
    private $normalizer;

    /**
     * @var ClassMetadataFactoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $classMetadata;

    protected function setUp()
    {
        $loader = $this->getMock('Makhan\Component\Serializer\Mapping\Loader\LoaderChain', array(), array(array()));
        $this->classMetadata = $this->getMock('Makhan\Component\Serializer\Mapping\Factory\ClassMetadataFactory', array(), array($loader));
        $this->normalizer = new AbstractNormalizerDummy($this->classMetadata);
    }

    public function testGetAllowedAttributesAsString()
    {
        $classMetadata = new ClassMetadata('c');

        $a1 = new AttributeMetadata('a1');
        $classMetadata->addAttributeMetadata($a1);

        $a2 = new AttributeMetadata('a2');
        $a2->addGroup('test');
        $classMetadata->addAttributeMetadata($a2);

        $a3 = new AttributeMetadata('a3');
        $a3->addGroup('other');
        $classMetadata->addAttributeMetadata($a3);

        $a4 = new AttributeMetadata('a4');
        $a4->addGroup('test');
        $a4->addGroup('other');
        $classMetadata->addAttributeMetadata($a4);

        $this->classMetadata->method('getMetadataFor')->willReturn($classMetadata);

        $result = $this->normalizer->getAllowedAttributes('c', array(AbstractNormalizer::GROUPS => array('test')), true);
        $this->assertEquals(array('a2', 'a4'), $result);

        $result = $this->normalizer->getAllowedAttributes('c', array(AbstractNormalizer::GROUPS => array('other')), true);
        $this->assertEquals(array('a3', 'a4'), $result);
    }

    public function testGetAllowedAttributesAsObjects()
    {
        $classMetadata = new ClassMetadata('c');

        $a1 = new AttributeMetadata('a1');
        $classMetadata->addAttributeMetadata($a1);

        $a2 = new AttributeMetadata('a2');
        $a2->addGroup('test');
        $classMetadata->addAttributeMetadata($a2);

        $a3 = new AttributeMetadata('a3');
        $a3->addGroup('other');
        $classMetadata->addAttributeMetadata($a3);

        $a4 = new AttributeMetadata('a4');
        $a4->addGroup('test');
        $a4->addGroup('other');
        $classMetadata->addAttributeMetadata($a4);

        $this->classMetadata->method('getMetadataFor')->willReturn($classMetadata);

        $result = $this->normalizer->getAllowedAttributes('c', array(AbstractNormalizer::GROUPS => array('test')), false);
        $this->assertEquals(array($a2, $a4), $result);

        $result = $this->normalizer->getAllowedAttributes('c', array(AbstractNormalizer::GROUPS => array('other')), false);
        $this->assertEquals(array($a3, $a4), $result);
    }

    public function testObjectToPopulateWithProxy()
    {
        $proxyDummy = new ProxyDummy();

        $context = array(AbstractNormalizer::OBJECT_TO_POPULATE => $proxyDummy);

        $normalizer = new ObjectNormalizer();
        $normalizer->denormalize(array('foo' => 'bar'), 'Makhan\Component\Serializer\Tests\Fixtures\ToBeProxyfiedDummy', null, $context);

        $this->assertSame('bar', $proxyDummy->getFoo());
    }
}
