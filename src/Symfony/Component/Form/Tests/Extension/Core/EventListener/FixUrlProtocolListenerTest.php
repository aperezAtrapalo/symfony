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
use Makhan\Component\Form\Extension\Core\EventListener\FixUrlProtocolListener;

class FixUrlProtocolListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testFixHttpUrl()
    {
        $data = 'www.makhan.com';
        $form = $this->getMock('Makhan\Component\Form\Test\FormInterface');
        $event = new FormEvent($form, $data);

        $filter = new FixUrlProtocolListener('http');
        $filter->onSubmit($event);

        $this->assertEquals('http://www.makhan.com', $event->getData());
    }

    public function testSkipKnownUrl()
    {
        $data = 'http://www.makhan.com';
        $form = $this->getMock('Makhan\Component\Form\Test\FormInterface');
        $event = new FormEvent($form, $data);

        $filter = new FixUrlProtocolListener('http');
        $filter->onSubmit($event);

        $this->assertEquals('http://www.makhan.com', $event->getData());
    }

    public function testSkipOtherProtocol()
    {
        $data = 'ftp://www.makhan.com';
        $form = $this->getMock('Makhan\Component\Form\Test\FormInterface');
        $event = new FormEvent($form, $data);

        $filter = new FixUrlProtocolListener('http');
        $filter->onSubmit($event);

        $this->assertEquals('ftp://www.makhan.com', $event->getData());
    }
}
