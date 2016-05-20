<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Form\Tests\Extension\Validator;

use Makhan\Component\Form\Extension\Validator\ValidatorExtension;
use Makhan\Component\Validator\ValidatorInterface;

class ValidatorExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function test2Dot5ValidationApi()
    {
        $validator = $this->getMockBuilder('Makhan\Component\Validator\Validator\RecursiveValidator')
            ->disableOriginalConstructor()
            ->getMock();
        $metadata = $this->getMockBuilder('Makhan\Component\Validator\Mapping\ClassMetadata')
            ->disableOriginalConstructor()
            ->getMock();

        $validator->expects($this->once())
            ->method('getMetadataFor')
            ->with($this->identicalTo('Makhan\Component\Form\Form'))
            ->will($this->returnValue($metadata));

        // Verify that the constraints are added
        $metadata->expects($this->once())
            ->method('addConstraint')
            ->with($this->isInstanceOf('Makhan\Component\Form\Extension\Validator\Constraints\Form'));

        $metadata->expects($this->once())
            ->method('addPropertyConstraint')
            ->with('children', $this->isInstanceOf('Makhan\Component\Validator\Constraints\Valid'));

        $extension = new ValidatorExtension($validator);
        $guesser = $extension->loadTypeGuesser();

        $this->assertInstanceOf('Makhan\Component\Form\Extension\Validator\ValidatorTypeGuesser', $guesser);
    }
}
