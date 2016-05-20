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
use Makhan\Bundle\FrameworkBundle\Templating\Helper\RequestHelper;

class RequestHelperTest extends \PHPUnit_Framework_TestCase
{
    protected $requestStack;

    protected function setUp()
    {
        $this->requestStack = new RequestStack();
        $request = new Request();
        $request->initialize(array('foobar' => 'bar'));
        $this->requestStack->push($request);
    }

    public function testGetParameter()
    {
        $helper = new RequestHelper($this->requestStack);

        $this->assertEquals('bar', $helper->getParameter('foobar'));
        $this->assertEquals('foo', $helper->getParameter('bar', 'foo'));

        $this->assertNull($helper->getParameter('foo'));
    }

    public function testGetLocale()
    {
        $helper = new RequestHelper($this->requestStack);

        $this->assertEquals('en', $helper->getLocale());
    }

    public function testGetName()
    {
        $helper = new RequestHelper($this->requestStack);

        $this->assertEquals('request', $helper->getName());
    }
}
