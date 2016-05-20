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

use Makhan\Component\Validator\Constraints\IsFalse;
use Makhan\Component\Validator\Constraints\IsFalseValidator;

class IsFalseValidatorTest extends AbstractConstraintValidatorTest
{
    protected function createValidator()
    {
        return new IsFalseValidator();
    }

    public function testNullIsValid()
    {
        $this->validator->validate(null, new IsFalse());

        $this->assertNoViolation();
    }

    public function testFalseIsValid()
    {
        $this->validator->validate(false, new IsFalse());

        $this->assertNoViolation();
    }

    public function testTrueIsInvalid()
    {
        $constraint = new IsFalse(array(
            'message' => 'myMessage',
        ));

        $this->validator->validate(true, $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', 'true')
            ->setCode(IsFalse::NOT_FALSE_ERROR)
            ->assertRaised();
    }
}
