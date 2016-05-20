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

use Makhan\Component\Validator\Constraints\Blank;
use Makhan\Component\Validator\Constraints\BlankValidator;

class BlankValidatorTest extends AbstractConstraintValidatorTest
{
    protected function createValidator()
    {
        return new BlankValidator();
    }

    public function testNullIsValid()
    {
        $this->validator->validate(null, new Blank());

        $this->assertNoViolation();
    }

    public function testBlankIsValid()
    {
        $this->validator->validate('', new Blank());

        $this->assertNoViolation();
    }

    /**
     * @dataProvider getInvalidValues
     */
    public function testInvalidValues($value, $valueAsString)
    {
        $constraint = new Blank(array(
            'message' => 'myMessage',
        ));

        $this->validator->validate($value, $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', $valueAsString)
            ->setCode(Blank::NOT_BLANK_ERROR)
            ->assertRaised();
    }

    public function getInvalidValues()
    {
        return array(
            array('foobar', '"foobar"'),
            array(0, '0'),
            array(false, 'false'),
            array(1234, '1234'),
        );
    }
}
