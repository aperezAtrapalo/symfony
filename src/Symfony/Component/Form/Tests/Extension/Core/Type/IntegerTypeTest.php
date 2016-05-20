<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Form\Tests\Extension\Core\Type;

use Makhan\Component\Form\Test\TypeTestCase as TestCase;
use Makhan\Component\Intl\Util\IntlTestHelper;

class IntegerTypeTest extends TestCase
{
    protected function setUp()
    {
        IntlTestHelper::requireIntl($this);

        parent::setUp();
    }

    public function testSubmitCastsToInteger()
    {
        $form = $this->factory->create('Makhan\Component\Form\Extension\Core\Type\IntegerType');

        $form->submit('1.678');

        $this->assertSame(1, $form->getData());
        $this->assertSame('1', $form->getViewData());
    }
}
