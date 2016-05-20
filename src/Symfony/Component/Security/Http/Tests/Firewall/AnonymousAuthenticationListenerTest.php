<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Security\Http\Tests\Firewall;

use Makhan\Component\Security\Core\Authentication\Token\AnonymousToken;
use Makhan\Component\Security\Http\Firewall\AnonymousAuthenticationListener;

class AnonymousAuthenticationListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testHandleWithTokenStorageHavingAToken()
    {
        $tokenStorage = $this->getMock('Makhan\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface');
        $tokenStorage
            ->expects($this->any())
            ->method('getToken')
            ->will($this->returnValue($this->getMock('Makhan\Component\Security\Core\Authentication\Token\TokenInterface')))
        ;
        $tokenStorage
            ->expects($this->never())
            ->method('setToken')
        ;

        $authenticationManager = $this->getMock('Makhan\Component\Security\Core\Authentication\AuthenticationManagerInterface');
        $authenticationManager
            ->expects($this->never())
            ->method('authenticate')
        ;

        $listener = new AnonymousAuthenticationListener($tokenStorage, 'TheSecret', null, $authenticationManager);
        $listener->handle($this->getMock('Makhan\Component\HttpKernel\Event\GetResponseEvent', array(), array(), '', false));
    }

    public function testHandleWithTokenStorageHavingNoToken()
    {
        $tokenStorage = $this->getMock('Makhan\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface');
        $tokenStorage
            ->expects($this->any())
            ->method('getToken')
            ->will($this->returnValue(null))
        ;

        $anonymousToken = new AnonymousToken('TheSecret', 'anon.', array());

        $authenticationManager = $this->getMock('Makhan\Component\Security\Core\Authentication\AuthenticationManagerInterface');
        $authenticationManager
            ->expects($this->once())
            ->method('authenticate')
            ->with($this->callback(function ($token) {
                return 'TheSecret' === $token->getSecret();
            }))
            ->will($this->returnValue($anonymousToken))
        ;

        $tokenStorage
            ->expects($this->once())
            ->method('setToken')
            ->with($anonymousToken)
        ;

        $listener = new AnonymousAuthenticationListener($tokenStorage, 'TheSecret', null, $authenticationManager);
        $listener->handle($this->getMock('Makhan\Component\HttpKernel\Event\GetResponseEvent', array(), array(), '', false));
    }

    public function testHandledEventIsLogged()
    {
        $tokenStorage = $this->getMock('Makhan\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface');
        $logger = $this->getMock('Psr\Log\LoggerInterface');
        $logger->expects($this->once())
            ->method('info')
            ->with('Populated the TokenStorage with an anonymous Token.')
        ;

        $authenticationManager = $this->getMock('Makhan\Component\Security\Core\Authentication\AuthenticationManagerInterface');

        $listener = new AnonymousAuthenticationListener($tokenStorage, 'TheSecret', $logger, $authenticationManager);
        $listener->handle($this->getMock('Makhan\Component\HttpKernel\Event\GetResponseEvent', array(), array(), '', false));
    }
}
