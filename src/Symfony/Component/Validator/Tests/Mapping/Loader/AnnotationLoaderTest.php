<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Validator\Tests\Mapping\Loader;

use Doctrine\Common\Annotations\AnnotationReader;
use Makhan\Component\Validator\Constraints\All;
use Makhan\Component\Validator\Constraints\Callback;
use Makhan\Component\Validator\Constraints\Choice;
use Makhan\Component\Validator\Constraints\Collection;
use Makhan\Component\Validator\Constraints\NotNull;
use Makhan\Component\Validator\Constraints\Range;
use Makhan\Component\Validator\Constraints\IsTrue;
use Makhan\Component\Validator\Mapping\ClassMetadata;
use Makhan\Component\Validator\Mapping\Loader\AnnotationLoader;
use Makhan\Component\Validator\Tests\Fixtures\ConstraintA;

class AnnotationLoaderTest extends \PHPUnit_Framework_TestCase
{
    public function testLoadClassMetadataReturnsTrueIfSuccessful()
    {
        $reader = new AnnotationReader();
        $loader = new AnnotationLoader($reader);
        $metadata = new ClassMetadata('Makhan\Component\Validator\Tests\Fixtures\Entity');

        $this->assertTrue($loader->loadClassMetadata($metadata));
    }

    public function testLoadClassMetadataReturnsFalseIfNotSuccessful()
    {
        $loader = new AnnotationLoader(new AnnotationReader());
        $metadata = new ClassMetadata('\stdClass');

        $this->assertFalse($loader->loadClassMetadata($metadata));
    }

    public function testLoadClassMetadata()
    {
        $loader = new AnnotationLoader(new AnnotationReader());
        $metadata = new ClassMetadata('Makhan\Component\Validator\Tests\Fixtures\Entity');

        $loader->loadClassMetadata($metadata);

        $expected = new ClassMetadata('Makhan\Component\Validator\Tests\Fixtures\Entity');
        $expected->setGroupSequence(array('Foo', 'Entity'));
        $expected->addConstraint(new ConstraintA());
        $expected->addConstraint(new Callback(array('Makhan\Component\Validator\Tests\Fixtures\CallbackClass', 'callback')));
        $expected->addConstraint(new Callback(array('callback' => 'validateMe', 'payload' => 'foo')));
        $expected->addConstraint(new Callback('validateMeStatic'));
        $expected->addPropertyConstraint('firstName', new NotNull());
        $expected->addPropertyConstraint('firstName', new Range(array('min' => 3)));
        $expected->addPropertyConstraint('firstName', new All(array(new NotNull(), new Range(array('min' => 3)))));
        $expected->addPropertyConstraint('firstName', new All(array('constraints' => array(new NotNull(), new Range(array('min' => 3))))));
        $expected->addPropertyConstraint('firstName', new Collection(array('fields' => array(
            'foo' => array(new NotNull(), new Range(array('min' => 3))),
            'bar' => new Range(array('min' => 5)),
        ))));
        $expected->addPropertyConstraint('firstName', new Choice(array(
            'message' => 'Must be one of %choices%',
            'choices' => array('A', 'B'),
        )));
        $expected->addGetterConstraint('lastName', new NotNull());
        $expected->addGetterConstraint('valid', new IsTrue());
        $expected->addGetterConstraint('permissions', new IsTrue());

        // load reflection class so that the comparison passes
        $expected->getReflectionClass();

        $this->assertEquals($expected, $metadata);
    }

    /**
     * Test MetaData merge with parent annotation.
     */
    public function testLoadParentClassMetadata()
    {
        $loader = new AnnotationLoader(new AnnotationReader());

        // Load Parent MetaData
        $parent_metadata = new ClassMetadata('Makhan\Component\Validator\Tests\Fixtures\EntityParent');
        $loader->loadClassMetadata($parent_metadata);

        $expected_parent = new ClassMetadata('Makhan\Component\Validator\Tests\Fixtures\EntityParent');
        $expected_parent->addPropertyConstraint('other', new NotNull());
        $expected_parent->getReflectionClass();

        $this->assertEquals($expected_parent, $parent_metadata);
    }

    /**
     * Test MetaData merge with parent annotation.
     */
    public function testLoadClassMetadataAndMerge()
    {
        $loader = new AnnotationLoader(new AnnotationReader());

        // Load Parent MetaData
        $parent_metadata = new ClassMetadata('Makhan\Component\Validator\Tests\Fixtures\EntityParent');
        $loader->loadClassMetadata($parent_metadata);

        $metadata = new ClassMetadata('Makhan\Component\Validator\Tests\Fixtures\Entity');

        // Merge parent metaData.
        $metadata->mergeConstraints($parent_metadata);

        $loader->loadClassMetadata($metadata);

        $expected_parent = new ClassMetadata('Makhan\Component\Validator\Tests\Fixtures\EntityParent');
        $expected_parent->addPropertyConstraint('other', new NotNull());
        $expected_parent->getReflectionClass();

        $expected = new ClassMetadata('Makhan\Component\Validator\Tests\Fixtures\Entity');
        $expected->mergeConstraints($expected_parent);

        $expected->setGroupSequence(array('Foo', 'Entity'));
        $expected->addConstraint(new ConstraintA());
        $expected->addConstraint(new Callback(array('Makhan\Component\Validator\Tests\Fixtures\CallbackClass', 'callback')));
        $expected->addConstraint(new Callback(array('callback' => 'validateMe', 'payload' => 'foo')));
        $expected->addConstraint(new Callback('validateMeStatic'));
        $expected->addPropertyConstraint('firstName', new NotNull());
        $expected->addPropertyConstraint('firstName', new Range(array('min' => 3)));
        $expected->addPropertyConstraint('firstName', new All(array(new NotNull(), new Range(array('min' => 3)))));
        $expected->addPropertyConstraint('firstName', new All(array('constraints' => array(new NotNull(), new Range(array('min' => 3))))));
        $expected->addPropertyConstraint('firstName', new Collection(array('fields' => array(
            'foo' => array(new NotNull(), new Range(array('min' => 3))),
            'bar' => new Range(array('min' => 5)),
        ))));
        $expected->addPropertyConstraint('firstName', new Choice(array(
            'message' => 'Must be one of %choices%',
            'choices' => array('A', 'B'),
        )));
        $expected->addGetterConstraint('lastName', new NotNull());
        $expected->addGetterConstraint('valid', new IsTrue());
        $expected->addGetterConstraint('permissions', new IsTrue());

        // load reflection class so that the comparison passes
        $expected->getReflectionClass();

        $this->assertEquals($expected, $metadata);
    }

    public function testLoadGroupSequenceProviderAnnotation()
    {
        $loader = new AnnotationLoader(new AnnotationReader());

        $metadata = new ClassMetadata('Makhan\Component\Validator\Tests\Fixtures\GroupSequenceProviderEntity');
        $loader->loadClassMetadata($metadata);

        $expected = new ClassMetadata('Makhan\Component\Validator\Tests\Fixtures\GroupSequenceProviderEntity');
        $expected->setGroupSequenceProvider(true);
        $expected->getReflectionClass();

        $this->assertEquals($expected, $metadata);
    }
}
