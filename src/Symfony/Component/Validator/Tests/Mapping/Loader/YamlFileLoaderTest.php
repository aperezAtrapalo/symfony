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

use Makhan\Component\Validator\Constraints\All;
use Makhan\Component\Validator\Constraints\Callback;
use Makhan\Component\Validator\Constraints\Choice;
use Makhan\Component\Validator\Constraints\Collection;
use Makhan\Component\Validator\Constraints\NotNull;
use Makhan\Component\Validator\Constraints\Range;
use Makhan\Component\Validator\Constraints\IsTrue;
use Makhan\Component\Validator\Mapping\ClassMetadata;
use Makhan\Component\Validator\Mapping\Loader\YamlFileLoader;
use Makhan\Component\Validator\Tests\Fixtures\ConstraintA;
use Makhan\Component\Validator\Tests\Fixtures\ConstraintB;

class YamlFileLoaderTest extends \PHPUnit_Framework_TestCase
{
    public function testLoadClassMetadataReturnsFalseIfEmpty()
    {
        $loader = new YamlFileLoader(__DIR__.'/empty-mapping.yml');
        $metadata = new ClassMetadata('Makhan\Component\Validator\Tests\Fixtures\Entity');

        $this->assertFalse($loader->loadClassMetadata($metadata));
    }

    /**
     * @dataProvider provideInvalidYamlFiles
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidYamlFiles($path)
    {
        $loader = new YamlFileLoader(__DIR__.'/'.$path);
        $metadata = new ClassMetadata('Makhan\Component\Validator\Tests\Fixtures\Entity');

        $loader->loadClassMetadata($metadata);
    }

    public function provideInvalidYamlFiles()
    {
        return array(
            array('nonvalid-mapping.yml'),
            array('bad-format.yml'),
        );
    }

    /**
     * @see https://github.com/makhan/makhan/pull/12158
     */
    public function testDoNotModifyStateIfExceptionIsThrown()
    {
        $loader = new YamlFileLoader(__DIR__.'/nonvalid-mapping.yml');
        $metadata = new ClassMetadata('Makhan\Component\Validator\Tests\Fixtures\Entity');
        try {
            $loader->loadClassMetadata($metadata);
        } catch (\InvalidArgumentException $e) {
            // Call again. Again an exception should be thrown
            $this->setExpectedException('\InvalidArgumentException');
            $loader->loadClassMetadata($metadata);
        }
    }

    public function testLoadClassMetadataReturnsTrueIfSuccessful()
    {
        $loader = new YamlFileLoader(__DIR__.'/constraint-mapping.yml');
        $metadata = new ClassMetadata('Makhan\Component\Validator\Tests\Fixtures\Entity');

        $this->assertTrue($loader->loadClassMetadata($metadata));
    }

    public function testLoadClassMetadataReturnsFalseIfNotSuccessful()
    {
        $loader = new YamlFileLoader(__DIR__.'/constraint-mapping.yml');
        $metadata = new ClassMetadata('\stdClass');

        $this->assertFalse($loader->loadClassMetadata($metadata));
    }

    public function testLoadClassMetadata()
    {
        $loader = new YamlFileLoader(__DIR__.'/constraint-mapping.yml');
        $metadata = new ClassMetadata('Makhan\Component\Validator\Tests\Fixtures\Entity');

        $loader->loadClassMetadata($metadata);

        $expected = new ClassMetadata('Makhan\Component\Validator\Tests\Fixtures\Entity');
        $expected->setGroupSequence(array('Foo', 'Entity'));
        $expected->addConstraint(new ConstraintA());
        $expected->addConstraint(new ConstraintB());
        $expected->addConstraint(new Callback('validateMe'));
        $expected->addConstraint(new Callback('validateMeStatic'));
        $expected->addConstraint(new Callback(array('Makhan\Component\Validator\Tests\Fixtures\CallbackClass', 'callback')));
        $expected->addPropertyConstraint('firstName', new NotNull());
        $expected->addPropertyConstraint('firstName', new Range(array('min' => 3)));
        $expected->addPropertyConstraint('firstName', new Choice(array('A', 'B')));
        $expected->addPropertyConstraint('firstName', new All(array(new NotNull(), new Range(array('min' => 3)))));
        $expected->addPropertyConstraint('firstName', new All(array('constraints' => array(new NotNull(), new Range(array('min' => 3))))));
        $expected->addPropertyConstraint('firstName', new Collection(array('fields' => array(
            'foo' => array(new NotNull(), new Range(array('min' => 3))),
            'bar' => array(new Range(array('min' => 5))),
        ))));
        $expected->addPropertyConstraint('firstName', new Choice(array(
            'message' => 'Must be one of %choices%',
            'choices' => array('A', 'B'),
        )));
        $expected->addGetterConstraint('lastName', new NotNull());
        $expected->addGetterConstraint('valid', new IsTrue());
        $expected->addGetterConstraint('permissions', new IsTrue());

        $this->assertEquals($expected, $metadata);
    }

    public function testLoadGroupSequenceProvider()
    {
        $loader = new YamlFileLoader(__DIR__.'/constraint-mapping.yml');
        $metadata = new ClassMetadata('Makhan\Component\Validator\Tests\Fixtures\GroupSequenceProviderEntity');

        $loader->loadClassMetadata($metadata);

        $expected = new ClassMetadata('Makhan\Component\Validator\Tests\Fixtures\GroupSequenceProviderEntity');
        $expected->setGroupSequenceProvider(true);

        $this->assertEquals($expected, $metadata);
    }
}
