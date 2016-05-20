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

class UrlTypeTest extends TestCase
{
    public function testSubmitAddsDefaultProtocolIfNoneIsIncluded()
    {
        $form = $this->factory->create('Makhan\Component\Form\Extension\Core\Type\UrlType', 'name');

        $form->submit('www.domain.com');

        $this->assertSame('http://www.domain.com', $form->getData());
        $this->assertSame('http://www.domain.com', $form->getViewData());
    }

    public function testSubmitAddsNoDefaultProtocolIfAlreadyIncluded()
    {
        $form = $this->factory->create('Makhan\Component\Form\Extension\Core\Type\UrlType', null, array(
            'default_protocol' => 'http',
        ));

        $form->submit('ftp://www.domain.com');

        $this->assertSame('ftp://www.domain.com', $form->getData());
        $this->assertSame('ftp://www.domain.com', $form->getViewData());
    }

    public function testSubmitAddsNoDefaultProtocolIfEmpty()
    {
        $form = $this->factory->create('Makhan\Component\Form\Extension\Core\Type\UrlType', null, array(
            'default_protocol' => 'http',
        ));

        $form->submit('');

        $this->assertNull($form->getData());
        $this->assertSame('', $form->getViewData());
    }

    public function testSubmitAddsNoDefaultProtocolIfNull()
    {
        $form = $this->factory->create('Makhan\Component\Form\Extension\Core\Type\UrlType', null, array(
            'default_protocol' => 'http',
        ));

        $form->submit(null);

        $this->assertNull($form->getData());
        $this->assertSame('', $form->getViewData());
    }

    public function testSubmitAddsNoDefaultProtocolIfSetToNull()
    {
        $form = $this->factory->create('Makhan\Component\Form\Extension\Core\Type\UrlType', null, array(
            'default_protocol' => null,
        ));

        $form->submit('www.domain.com');

        $this->assertSame('www.domain.com', $form->getData());
        $this->assertSame('www.domain.com', $form->getViewData());
    }

    /**
     * @expectedException \Makhan\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    public function testThrowExceptionIfDefaultProtocolIsInvalid()
    {
        $this->factory->create('Makhan\Component\Form\Extension\Core\Type\UrlType', null, array(
            'default_protocol' => array(),
        ));
    }
}
