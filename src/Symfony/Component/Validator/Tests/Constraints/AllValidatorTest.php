<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Validator\Tests\Constraints;

use Makhan\Component\Validator\Constraints\All;
use Makhan\Component\Validator\Constraints\AllValidator;
use Makhan\Component\Validator\Constraints\NotNull;
use Makhan\Component\Validator\Constraints\Range;

class AllValidatorTest extends AbstractConstraintValidatorTest
{
    protected function createValidator()
    {
        return new AllValidator();
    }

    public function testNullIsValid()
    {
        $this->validator->validate(null, new All(new Range(array('min' => 4))));

        $this->assertNoViolation();
    }

    /**
     * @expectedException \Makhan\Component\Validator\Exception\UnexpectedTypeException
     */
    public function testThrowsExceptionIfNotTraversable()
    {
        $this->validator->validate('foo.barbar', new All(new Range(array('min' => 4))));
    }

    /**
     * @dataProvider getValidArguments
     */
    public function testWalkSingleConstraint($array)
    {
        $constraint = new Range(array('min' => 4));

        $i = 0;

        foreach ($array as $key => $value) {
            $this->expectValidateValueAt($i++, '['.$key.']', $value, array($constraint));
        }

        $this->validator->validate($array, new All($constraint));

        $this->assertNoViolation();
    }

    /**
     * @dataProvider getValidArguments
     */
    public function testWalkMultipleConstraints($array)
    {
        $constraint1 = new Range(array('min' => 4));
        $constraint2 = new NotNull();

        $constraints = array($constraint1, $constraint2);

        $i = 0;

        foreach ($array as $key => $value) {
            $this->expectValidateValueAt($i++, '['.$key.']', $value, array($constraint1, $constraint2));
        }

        $this->validator->validate($array, new All($constraints));

        $this->assertNoViolation();
    }

    public function getValidArguments()
    {
        return array(
            array(array(5, 6, 7)),
            array(new \ArrayObject(array(5, 6, 7))),
        );
    }
}
