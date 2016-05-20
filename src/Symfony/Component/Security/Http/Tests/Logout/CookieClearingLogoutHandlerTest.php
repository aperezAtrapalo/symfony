<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Security\Http\Tests\Logout;

use Makhan\Component\HttpFoundation\Response;
use Makhan\Component\HttpFoundation\ResponseHeaderBag;
use Makhan\Component\HttpFoundation\Request;
use Makhan\Component\Security\Http\Logout\CookieClearingLogoutHandler;

class CookieClearingLogoutHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testLogout()
    {
        $request = new Request();
        $response = new Response();
        $token = $this->getMock('Makhan\Component\Security\Core\Authentication\Token\TokenInterface');

        $handler = new CookieClearingLogoutHandler(array('foo' => array('path' => '/foo', 'domain' => 'foo.foo'), 'foo2' => array('path' => null, 'domain' => null)));

        $cookies = $response->headers->getCookies();
        $this->assertCount(0, $cookies);

        $handler->logout($request, $response, $token);

        $cookies = $response->headers->getCookies(ResponseHeaderBag::COOKIES_ARRAY);
        $this->assertCount(2, $cookies);

        $cookie = $cookies['foo.foo']['/foo']['foo'];
        $this->assertEquals('foo', $cookie->getName());
        $this->assertEquals('/foo', $cookie->getPath());
        $this->assertEquals('foo.foo', $cookie->getDomain());
        $this->assertTrue($cookie->isCleared());

        $cookie = $cookies['']['/']['foo2'];
        $this->assertStringStartsWith('foo2', $cookie->getName());
        $this->assertEquals('/', $cookie->getPath());
        $this->assertNull($cookie->getDomain());
        $this->assertTrue($cookie->isCleared());
    }
}
