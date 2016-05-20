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

use Makhan\Component\Validator\Constraints\NotBlank;
use Makhan\Component\Validator\Constraints\NotBlankValidator;

class NotBlankValidatorTest extends AbstractConstraintValidatorTest
{
    protected function createValidator()
    {
        return new NotBlankValidator();
    }

    /**
     * @dataProvider getValidValues
     */
    public function testValidValues($value)
    {
        $this->validator->validate($value, new NotBlank());

        $this->assertNoViolation();
    }

    public function getValidValues()
    {
        return array(
            array('foobar'),
            array(0),
            array(0.0),
            array('0'),
            array(1234),
        );
    }

    public function testNullIsInvalid()
    {
        $constraint = new NotBlank(array(
            'message' => 'myMessage',
        ));

        $this->validator->validate(null, $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', 'null')
            ->setCode(NotBlank::IS_BLANK_ERROR)
            ->assertRaised();
    }

    public function testBlankIsInvalid()
    {
        $constraint = new NotBlank(array(
            'message' => 'myMessage',
        ));

        $this->validator->validate('', $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', '""')
            ->setCode(NotBlank::IS_BLANK_ERROR)
            ->assertRaised();
    }

    public function testFalseIsInvalid()
    {
        $constraint = new NotBlank(array(
            'message' => 'myMessage',
        ));

        $this->validator->validate(false, $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', 'false')
            ->setCode(NotBlank::IS_BLANK_ERROR)
            ->assertRaised();
    }

    public function testEmptyArrayIsInvalid()
    {
        $constraint = new NotBlank(array(
            'message' => 'myMessage',
        ));

        $this->validator->validate(array(), $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', 'array')
            ->setCode(NotBlank::IS_BLANK_ERROR)
            ->assertRaised();
    }
}
