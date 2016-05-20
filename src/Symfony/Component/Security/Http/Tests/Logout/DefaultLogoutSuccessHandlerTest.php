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

use Makhan\Component\Security\Http\Logout\DefaultLogoutSuccessHandler;

class DefaultLogoutSuccessHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testLogout()
    {
        $request = $this->getMock('Makhan\Component\HttpFoundation\Request');
        $response = $this->getMock('Makhan\Component\HttpFoundation\Response');

        $httpUtils = $this->getMock('Makhan\Component\Security\Http\HttpUtils');
        $httpUtils->expects($this->once())
            ->method('createRedirectResponse')
            ->with($request, '/dashboard')
            ->will($this->returnValue($response));

        $handler = new DefaultLogoutSuccessHandler($httpUtils, '/dashboard');
        $result = $handler->onLogoutSuccess($request);

        $this->assertSame($response, $result);
    }
}
