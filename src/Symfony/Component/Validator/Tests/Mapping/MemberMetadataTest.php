<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Validator\Tests\Mapping;

use Makhan\Component\Validator\Constraints\Valid;
use Makhan\Component\Validator\Mapping\MemberMetadata;
use Makhan\Component\Validator\Tests\Fixtures\ClassConstraint;
use Makhan\Component\Validator\Tests\Fixtures\ConstraintA;
use Makhan\Component\Validator\Tests\Fixtures\ConstraintB;

class MemberMetadataTest extends \PHPUnit_Framework_TestCase
{
    protected $metadata;

    protected function setUp()
    {
        $this->metadata = new TestMemberMetadata(
            'Makhan\Component\Validator\Tests\Fixtures\Entity',
            'getLastName',
            'lastName'
        );
    }

    protected function tearDown()
    {
        $this->metadata = null;
    }

    public function testAddConstraintRequiresClassConstraints()
    {
        $this->setExpectedException('Makhan\Component\Validator\Exception\ConstraintDefinitionException');

        $this->metadata->addConstraint(new ClassConstraint());
    }

    public function testSerialize()
    {
        $this->metadata->addConstraint(new ConstraintA(array('property1' => 'A')));
        $this->metadata->addConstraint(new ConstraintB(array('groups' => 'TestGroup')));

        $metadata = unserialize(serialize($this->metadata));

        $this->assertEquals($this->metadata, $metadata);
    }

    public function testSerializeCollectionCascaded()
    {
        $this->metadata->addConstraint(new Valid(array('traverse' => true)));

        $metadata = unserialize(serialize($this->metadata));

        $this->assertEquals($this->metadata, $metadata);
    }

    public function testSerializeCollectionNotCascaded()
    {
        $this->metadata->addConstraint(new Valid(array('traverse' => false)));

        $metadata = unserialize(serialize($this->metadata));

        $this->assertEquals($this->metadata, $metadata);
    }
}

class TestMemberMetadata extends MemberMetadata
{
    public function getPropertyValue($object)
    {
    }

    protected function newReflectionMember($object)
    {
    }
}
