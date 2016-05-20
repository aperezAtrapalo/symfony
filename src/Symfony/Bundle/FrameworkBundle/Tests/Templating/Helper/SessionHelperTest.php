<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\FrameworkBundle\Tests\Templating\Helper;

use Makhan\Component\HttpFoundation\Request;
use Makhan\Component\HttpFoundation\RequestStack;
use Makhan\Component\HttpFoundation\Session\Session;
use Makhan\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Makhan\Bundle\FrameworkBundle\Templating\Helper\SessionHelper;

class SessionHelperTest extends \PHPUnit_Framework_TestCase
{
    protected $requestStack;

    protected function setUp()
    {
        $request = new Request();

        $session = new Session(new MockArraySessionStorage());
        $session->set('foobar', 'bar');
        $session->getFlashBag()->set('notice', 'bar');

        $request->setSession($session);

        $this->requestStack = new RequestStack();
        $this->requestStack->push($request);
    }

    protected function tearDown()
    {
        $this->requestStack = null;
    }

    public function testFlash()
    {
        $helper = new SessionHelper($this->requestStack);

        $this->assertTrue($helper->hasFlash('notice'));

        $this->assertEquals(array('bar'), $helper->getFlash('notice'));
    }

    public function testGetFlashes()
    {
        $helper = new SessionHelper($this->requestStack);
        $this->assertEquals(array('notice' => array('bar')), $helper->getFlashes());
    }

    public function testGet()
    {
        $helper = new SessionHelper($this->requestStack);

        $this->assertEquals('bar', $helper->get('foobar'));
        $this->assertEquals('foo', $helper->get('bar', 'foo'));

        $this->assertNull($helper->get('foo'));
    }

    public function testGetName()
    {
        $helper = new SessionHelper($this->requestStack);

        $this->assertEquals('session', $helper->getName());
    }
}
