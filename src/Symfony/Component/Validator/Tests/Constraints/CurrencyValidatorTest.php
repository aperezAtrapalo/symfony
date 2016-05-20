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

use Makhan\Component\Intl\Util\IntlTestHelper;
use Makhan\Component\Validator\Constraints\Currency;
use Makhan\Component\Validator\Constraints\CurrencyValidator;

class CurrencyValidatorTest extends AbstractConstraintValidatorTest
{
    protected function createValidator()
    {
        return new CurrencyValidator();
    }

    public function testNullIsValid()
    {
        $this->validator->validate(null, new Currency());

        $this->assertNoViolation();
    }

    public function testEmptyStringIsValid()
    {
        $this->validator->validate('', new Currency());

        $this->assertNoViolation();
    }

    /**
     * @expectedException \Makhan\Component\Validator\Exception\UnexpectedTypeException
     */
    public function testExpectsStringCompatibleType()
    {
        $this->validator->validate(new \stdClass(), new Currency());
    }

    /**
     * @dataProvider getValidCurrencies
     */
    public function testValidCurrencies($currency)
    {
        $this->validator->validate($currency, new Currency());

        $this->assertNoViolation();
    }

    /**
     * @dataProvider getValidCurrencies
     **/
    public function testValidCurrenciesWithCountrySpecificLocale($currency)
    {
        IntlTestHelper::requireFullIntl($this);

        \Locale::setDefault('en_GB');

        $this->validator->validate($currency, new Currency());

        $this->assertNoViolation();
    }

    public function getValidCurrencies()
    {
        return array(
            array('EUR'),
            array('USD'),
            array('SIT'),
            array('AUD'),
            array('CAD'),
        );
    }

    /**
     * @dataProvider getInvalidCurrencies
     */
    public function testInvalidCurrencies($currency)
    {
        $constraint = new Currency(array(
            'message' => 'myMessage',
        ));

        $this->validator->validate($currency, $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', '"'.$currency.'"')
            ->setCode(Currency::NO_SUCH_CURRENCY_ERROR)
            ->assertRaised();
    }

    public function getInvalidCurrencies()
    {
        return array(
            array('EN'),
            array('foobar'),
        );
    }
}
