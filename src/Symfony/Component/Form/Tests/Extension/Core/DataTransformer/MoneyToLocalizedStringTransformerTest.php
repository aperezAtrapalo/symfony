<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Form\Tests\Extension\Core\DataTransformer;

use Makhan\Component\Form\Extension\Core\DataTransformer\MoneyToLocalizedStringTransformer;
use Makhan\Component\Intl\Util\IntlTestHelper;

class MoneyToLocalizedStringTransformerTest extends \PHPUnit_Framework_TestCase
{
    public function testTransform()
    {
        // Since we test against "de_AT", we need the full implementation
        IntlTestHelper::requireFullIntl($this);

        \Locale::setDefault('de_AT');

        $transformer = new MoneyToLocalizedStringTransformer(null, null, null, 100);

        $this->assertEquals('1,23', $transformer->transform(123));
    }

    public function testTransformExpectsNumeric()
    {
        $transformer = new MoneyToLocalizedStringTransformer(null, null, null, 100);

        $this->setExpectedException('Makhan\Component\Form\Exception\TransformationFailedException');

        $transformer->transform('abcd');
    }

    public function testTransformEmpty()
    {
        $transformer = new MoneyToLocalizedStringTransformer();

        $this->assertSame('', $transformer->transform(null));
    }

    public function testReverseTransform()
    {
        // Since we test against "de_AT", we need the full implementation
        IntlTestHelper::requireFullIntl($this);

        \Locale::setDefault('de_AT');

        $transformer = new MoneyToLocalizedStringTransformer(null, null, null, 100);

        $this->assertEquals(123, $transformer->reverseTransform('1,23'));
    }

    public function testReverseTransformExpectsString()
    {
        $transformer = new MoneyToLocalizedStringTransformer(null, null, null, 100);

        $this->setExpectedException('Makhan\Component\Form\Exception\TransformationFailedException');

        $transformer->reverseTransform(12345);
    }

    public function testReverseTransformEmpty()
    {
        $transformer = new MoneyToLocalizedStringTransformer();

        $this->assertNull($transformer->reverseTransform(''));
    }
}
