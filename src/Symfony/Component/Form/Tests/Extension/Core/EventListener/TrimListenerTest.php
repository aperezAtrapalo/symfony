<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Form\Tests\Extension\Core\EventListener;

use Makhan\Component\Form\FormEvent;
use Makhan\Component\Form\Extension\Core\EventListener\TrimListener;

class TrimListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testTrim()
    {
        $data = ' Foo! ';
        $form = $this->getMock('Makhan\Component\Form\Test\FormInterface');
        $event = new FormEvent($form, $data);

        $filter = new TrimListener();
        $filter->preSubmit($event);

        $this->assertEquals('Foo!', $event->getData());
    }

    public function testTrimSkipNonStrings()
    {
        $data = 1234;
        $form = $this->getMock('Makhan\Component\Form\Test\FormInterface');
        $event = new FormEvent($form, $data);

        $filter = new TrimListener();
        $filter->preSubmit($event);

        $this->assertSame(1234, $event->getData());
    }
}
