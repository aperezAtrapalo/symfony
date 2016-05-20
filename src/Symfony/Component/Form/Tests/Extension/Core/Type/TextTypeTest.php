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

class TextTypeTest extends TestCase
{
    public function testSubmitNullReturnsNull()
    {
        $form = $this->factory->create('Makhan\Component\Form\Extension\Core\Type\TextType', 'name');

        $form->submit(null);

        $this->assertNull($form->getData());
    }

    public function testSubmitNullReturnsEmptyStringWithEmptyDataAsString()
    {
        $form = $this->factory->create('Makhan\Component\Form\Extension\Core\Type\TextType', 'name', array(
            'empty_data' => '',
        ));

        $form->submit(null);

        $this->assertSame('', $form->getData());
    }
}
