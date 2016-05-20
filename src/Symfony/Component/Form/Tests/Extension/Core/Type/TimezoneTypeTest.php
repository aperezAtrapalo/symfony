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

use Makhan\Component\Form\ChoiceList\View\ChoiceView;

class TimezoneTypeTest extends \Makhan\Component\Form\Test\TypeTestCase
{
    public function testTimezonesAreSelectable()
    {
        $form = $this->factory->create('Makhan\Component\Form\Extension\Core\Type\TimezoneType');
        $view = $form->createView();
        $choices = $view->vars['choices'];

        $this->assertArrayHasKey('Africa', $choices);
        $this->assertContains(new ChoiceView('Africa/Kinshasa', 'Africa/Kinshasa', 'Kinshasa'), $choices['Africa'], '', false, false);

        $this->assertArrayHasKey('America', $choices);
        $this->assertContains(new ChoiceView('America/New_York', 'America/New_York', 'New York'), $choices['America'], '', false, false);
    }
}
