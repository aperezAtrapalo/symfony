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
use Makhan\Component\Security\Http\Logout\SessionLogoutHandler;

class SessionLogoutHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testLogout()
    {
        $handler = new SessionLogoutHandler();

        $request = $this->getMock('Makhan\Component\HttpFoundation\Request');
        $response = new Response();
        $session = $this->getMock('Makhan\Component\HttpFoundation\Session\Session', array(), array(), '', false);

        $request
            ->expects($this->once())
            ->method('getSession')
            ->will($this->returnValue($session))
        ;

        $session
            ->expects($this->once())
            ->method('invalidate')
        ;

        $handler->logout($request, $response, $this->getMock('Makhan\Component\Security\Core\Authentication\Token\TokenInterface'));
    }
}
