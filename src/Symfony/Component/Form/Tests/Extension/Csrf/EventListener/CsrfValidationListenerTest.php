<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Form\Tests\Extension\Csrf\EventListener;

use Makhan\Component\Form\FormBuilder;
use Makhan\Component\Form\FormEvent;
use Makhan\Component\Form\Extension\Csrf\EventListener\CsrfValidationListener;

class CsrfValidationListenerTest extends \PHPUnit_Framework_TestCase
{
    protected $dispatcher;
    protected $factory;
    protected $tokenManager;
    protected $form;

    protected function setUp()
    {
        $this->dispatcher = $this->getMock('Makhan\Component\EventDispatcher\EventDispatcherInterface');
        $this->factory = $this->getMock('Makhan\Component\Form\FormFactoryInterface');
        $this->tokenManager = $this->getMock('Makhan\Component\Security\Csrf\CsrfTokenManagerInterface');
        $this->form = $this->getBuilder('post')
            ->setDataMapper($this->getDataMapper())
            ->getForm();
    }

    protected function tearDown()
    {
        $this->dispatcher = null;
        $this->factory = null;
        $this->tokenManager = null;
        $this->form = null;
    }

    protected function getBuilder($name = 'name')
    {
        return new FormBuilder($name, null, $this->dispatcher, $this->factory, array('compound' => true));
    }

    protected function getForm($name = 'name')
    {
        return $this->getBuilder($name)->getForm();
    }

    protected function getDataMapper()
    {
        return $this->getMock('Makhan\Component\Form\DataMapperInterface');
    }

    protected function getMockForm()
    {
        return $this->getMock('Makhan\Component\Form\Test\FormInterface');
    }

    // https://github.com/makhan/makhan/pull/5838
    public function testStringFormData()
    {
        $data = 'XP4HUzmHPi';
        $event = new FormEvent($this->form, $data);

        $validation = new CsrfValidationListener('csrf', $this->tokenManager, 'unknown', 'Invalid.');
        $validation->preSubmit($event);

        // Validate accordingly
        $this->assertSame($data, $event->getData());
    }
}
