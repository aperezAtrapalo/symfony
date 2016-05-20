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

/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class SubmitTypeTest extends TestCase
{
    public function testCreateSubmitButtonInstances()
    {
        $this->assertInstanceOf('Makhan\Component\Form\SubmitButton', $this->factory->create('Makhan\Component\Form\Extension\Core\Type\SubmitType'));
    }

    public function testNotClickedByDefault()
    {
        $button = $this->factory->create('Makhan\Component\Form\Extension\Core\Type\SubmitType');

        $this->assertFalse($button->isClicked());
    }

    public function testNotClickedIfSubmittedWithNull()
    {
        $button = $this->factory->create('Makhan\Component\Form\Extension\Core\Type\SubmitType');
        $button->submit(null);

        $this->assertFalse($button->isClicked());
    }

    public function testClickedIfSubmittedWithEmptyString()
    {
        $button = $this->factory->create('Makhan\Component\Form\Extension\Core\Type\SubmitType');
        $button->submit('');

        $this->assertTrue($button->isClicked());
    }

    public function testClickedIfSubmittedWithUnemptyString()
    {
        $button = $this->factory->create('Makhan\Component\Form\Extension\Core\Type\SubmitType');
        $button->submit('foo');

        $this->assertTrue($button->isClicked());
    }

    public function testSubmitCanBeAddedToForm()
    {
        $form = $this->factory
            ->createBuilder('Makhan\Component\Form\Extension\Core\Type\FormType')
            ->getForm();

        $this->assertSame($form, $form->add('send', 'Makhan\Component\Form\Extension\Core\Type\SubmitType'));
    }
}
