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

use Makhan\Component\Validator\Constraints\IsTrue;
use Makhan\Component\Validator\Constraints\IsTrueValidator;

class IsTrueValidatorTest extends AbstractConstraintValidatorTest
{
    protected function createValidator()
    {
        return new IsTrueValidator();
    }

    public function testNullIsValid()
    {
        $this->validator->validate(null, new IsTrue());

        $this->assertNoViolation();
    }

    public function testTrueIsValid()
    {
        $this->validator->validate(true, new IsTrue());

        $this->assertNoViolation();
    }

    public function testFalseIsInvalid()
    {
        $constraint = new IsTrue(array(
            'message' => 'myMessage',
        ));

        $this->validator->validate(false, $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', 'false')
            ->setCode(IsTrue::NOT_TRUE_ERROR)
            ->assertRaised();
    }
}
