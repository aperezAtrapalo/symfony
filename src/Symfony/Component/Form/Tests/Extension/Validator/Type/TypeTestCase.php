<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Form\Tests\Extension\Validator\Type;

use Makhan\Component\Form\Test\TypeTestCase as BaseTypeTestCase;
use Makhan\Component\Form\Extension\Validator\ValidatorExtension;

abstract class TypeTestCase extends BaseTypeTestCase
{
    protected $validator;

    protected function setUp()
    {
        $this->validator = $this->getMock('Makhan\Component\Validator\Validator\ValidatorInterface');
        $metadata = $this->getMockBuilder('Makhan\Component\Validator\Mapping\ClassMetadata')->disableOriginalConstructor()->getMock();
        $this->validator->expects($this->once())->method('getMetadataFor')->will($this->returnValue($metadata));

        parent::setUp();
    }

    protected function tearDown()
    {
        $this->validator = null;

        parent::tearDown();
    }

    protected function getExtensions()
    {
        return array_merge(parent::getExtensions(), array(
            new ValidatorExtension($this->validator),
        ));
    }
}
