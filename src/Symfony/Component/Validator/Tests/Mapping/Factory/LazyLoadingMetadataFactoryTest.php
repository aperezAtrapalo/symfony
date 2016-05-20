<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Validator\Tests\Mapping\Factory;

use Makhan\Component\Validator\Mapping\ClassMetadata;
use Makhan\Component\Validator\Mapping\Factory\LazyLoadingMetadataFactory;
use Makhan\Component\Validator\Mapping\Loader\LoaderInterface;
use Makhan\Component\Validator\Tests\Fixtures\ConstraintA;

class LazyLoadingMetadataFactoryTest extends \PHPUnit_Framework_TestCase
{
    const CLASSNAME = 'Makhan\Component\Validator\Tests\Fixtures\Entity';
    const PARENTCLASS = 'Makhan\Component\Validator\Tests\Fixtures\EntityParent';

    public function testLoadClassMetadata()
    {
        $factory = new LazyLoadingMetadataFactory(new TestLoader());
        $metadata = $factory->getMetadataFor(self::PARENTCLASS);

        $constraints = array(
            new ConstraintA(array('groups' => array('Default', 'EntityParent'))),
        );

        $this->assertEquals($constraints, $metadata->getConstraints());
    }

    public function testMergeParentConstraints()
    {
        $factory = new LazyLoadingMetadataFactory(new TestLoader());
        $metadata = $factory->getMetadataFor(self::CLASSNAME);

        $constraints = array(
            new ConstraintA(array('groups' => array(
                'Default',
                'EntityParent',
                'Entity',
            ))),
            new ConstraintA(array('groups' => array(
                'Default',
                'EntityInterface',
                'Entity',
            ))),
            new ConstraintA(array('groups' => array(
                'Default',
                'Entity',
            ))),
        );

        $this->assertEquals($constraints, $metadata->getConstraints());
    }

    public function testWriteMetadataToCache()
    {
        $cache = $this->getMock('Makhan\Component\Validator\Mapping\Cache\CacheInterface');
        $factory = new LazyLoadingMetadataFactory(new TestLoader(), $cache);

        $constraints = array(
            new ConstraintA(array('groups' => array('Default', 'EntityParent'))),
        );

        $cache->expects($this->never())
              ->method('has');
        $cache->expects($this->once())
              ->method('read')
              ->with($this->equalTo(self::PARENTCLASS))
              ->will($this->returnValue(false));
        $cache->expects($this->once())
              ->method('write')
              ->will($this->returnCallback(function ($metadata) use ($constraints) {
                  $this->assertEquals($constraints, $metadata->getConstraints());
              }));

        $metadata = $factory->getMetadataFor(self::PARENTCLASS);

        $this->assertEquals(self::PARENTCLASS, $metadata->getClassName());
        $this->assertEquals($constraints, $metadata->getConstraints());
    }

    public function testReadMetadataFromCache()
    {
        $loader = $this->getMock('Makhan\Component\Validator\Mapping\Loader\LoaderInterface');
        $cache = $this->getMock('Makhan\Component\Validator\Mapping\Cache\CacheInterface');
        $factory = new LazyLoadingMetadataFactory($loader, $cache);

        $metadata = new ClassMetadata(self::PARENTCLASS);
        $metadata->addConstraint(new ConstraintA());

        $loader->expects($this->never())
               ->method('loadClassMetadata');

        $cache->expects($this->never())
              ->method('has');
        $cache->expects($this->once())
              ->method('read')
              ->will($this->returnValue($metadata));

        $this->assertEquals($metadata, $factory->getMetadataFor(self::PARENTCLASS));
    }
}

class TestLoader implements LoaderInterface
{
    public function loadClassMetadata(ClassMetadata $metadata)
    {
        $metadata->addConstraint(new ConstraintA());
    }
}
