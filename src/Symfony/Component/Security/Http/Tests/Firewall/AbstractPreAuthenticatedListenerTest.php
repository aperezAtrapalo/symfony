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

use Makhan\Component\HttpFoundation\Request;
use Makhan\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Makhan\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Makhan\Component\Security\Core\Exception\AuthenticationException;

class AbstractPreAuthenticatedListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testHandleWithValidValues()
    {
        $userCredentials = array('TheUser', 'TheCredentials');

        $request = new Request(array(), array(), array(), array(), array(), array());

        $token = $this->getMock('Makhan\Component\Security\Core\Authentication\Token\TokenInterface');

        $tokenStorage = $this->getMock('Makhan\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface');
        $tokenStorage
            ->expects($this->any())
            ->method('getToken')
            ->will($this->returnValue(null))
        ;
        $tokenStorage
            ->expects($this->once())
            ->method('setToken')
            ->with($this->equalTo($token))
        ;

        $authenticationManager = $this->getMock('Makhan\Component\Security\Core\Authentication\AuthenticationManagerInterface');
        $authenticationManager
            ->expects($this->once())
            ->method('authenticate')
            ->with($this->isInstanceOf('Makhan\Component\Security\Core\Authentication\Token\PreAuthenticatedToken'))
            ->will($this->returnValue($token))
        ;

        $listener = $this->getMockForAbstractClass('Makhan\Component\Security\Http\Firewall\AbstractPreAuthenticatedListener', array(
            $tokenStorage,
            $authenticationManager,
            'TheProviderKey',
        ));
        $listener
            ->expects($this->once())
            ->method('getPreAuthenticatedData')
            ->will($this->returnValue($userCredentials));

        $event = $this->getMock('Makhan\Component\HttpKernel\Event\GetResponseEvent', array(), array(), '', false);
        $event
            ->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($request))
        ;

        $listener->handle($event);
    }

    public function testHandleWhenAuthenticationFails()
    {
        $userCredentials = array('TheUser', 'TheCredentials');

        $request = new Request(array(), array(), array(), array(), array(), array());

        $tokenStorage = $this->getMock('Makhan\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface');
        $tokenStorage
            ->expects($this->any())
            ->method('getToken')
            ->will($this->returnValue(null))
        ;
        $tokenStorage
            ->expects($this->never())
            ->method('setToken')
        ;

        $exception = new AuthenticationException('Authentication failed.');
        $authenticationManager = $this->getMock('Makhan\Component\Security\Core\Authentication\AuthenticationManagerInterface');
        $authenticationManager
            ->expects($this->once())
            ->method('authenticate')
            ->with($this->isInstanceOf('Makhan\Component\Security\Core\Authentication\Token\PreAuthenticatedToken'))
            ->will($this->throwException($exception))
        ;

        $listener = $this->getMockForAbstractClass('Makhan\Component\Security\Http\Firewall\AbstractPreAuthenticatedListener', array(
            $tokenStorage,
            $authenticationManager,
            'TheProviderKey',
        ));
        $listener
            ->expects($this->once())
            ->method('getPreAuthenticatedData')
            ->will($this->returnValue($userCredentials));

        $event = $this->getMock('Makhan\Component\HttpKernel\Event\GetResponseEvent', array(), array(), '', false);
        $event
            ->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($request))
        ;

        $listener->handle($event);
    }

    public function testHandleWhenAuthenticationFailsWithDifferentToken()
    {
        $userCredentials = array('TheUser', 'TheCredentials');

        $token = new UsernamePasswordToken('TheUsername', 'ThePassword', 'TheProviderKey', array('ROLE_FOO'));

        $request = new Request(array(), array(), array(), array(), array(), array());

        $tokenStorage = $this->getMock('Makhan\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface');
        $tokenStorage
            ->expects($this->any())
            ->method('getToken')
            ->will($this->returnValue($token))
        ;
        $tokenStorage
            ->expects($this->never())
            ->method('setToken')
        ;

        $exception = new AuthenticationException('Authentication failed.');
        $authenticationManager = $this->getMock('Makhan\Component\Security\Core\Authentication\AuthenticationManagerInterface');
        $authenticationManager
            ->expects($this->once())
            ->method('authenticate')
            ->with($this->isInstanceOf('Makhan\Component\Security\Core\Authentication\Token\PreAuthenticatedToken'))
            ->will($this->throwException($exception))
        ;

        $listener = $this->getMockForAbstractClass('Makhan\Component\Security\Http\Firewall\AbstractPreAuthenticatedListener', array(
            $tokenStorage,
            $authenticationManager,
            'TheProviderKey',
        ));
        $listener
            ->expects($this->once())
            ->method('getPreAuthenticatedData')
            ->will($this->returnValue($userCredentials));

        $event = $this->getMock('Makhan\Component\HttpKernel\Event\GetResponseEvent', array(), array(), '', false);
        $event
            ->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($request))
        ;

        $listener->handle($event);
    }

    public function testHandleWithASimilarAuthenticatedToken()
    {
        $userCredentials = array('TheUser', 'TheCredentials');

        $request = new Request(array(), array(), array(), array(), array(), array());

        $token = new PreAuthenticatedToken('TheUser', 'TheCredentials', 'TheProviderKey', array('ROLE_FOO'));

        $tokenStorage = $this->getMock('Makhan\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface');
        $tokenStorage
            ->expects($this->any())
            ->method('getToken')
            ->will($this->returnValue($token))
        ;

        $authenticationManager = $this->getMock('Makhan\Component\Security\Core\Authentication\AuthenticationManagerInterface');
        $authenticationManager
            ->expects($this->never())
            ->method('authenticate')
        ;

        $listener = $this->getMockForAbstractClass('Makhan\Component\Security\Http\Firewall\AbstractPreAuthenticatedListener', array(
            $tokenStorage,
            $authenticationManager,
            'TheProviderKey',
        ));
        $listener
            ->expects($this->once())
            ->method('getPreAuthenticatedData')
            ->will($this->returnValue($userCredentials));

        $event = $this->getMock('Makhan\Component\HttpKernel\Event\GetResponseEvent', array(), array(), '', false);
        $event
            ->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($request))
        ;

        $listener->handle($event);
    }

    public function testHandleWithAnInvalidSimilarToken()
    {
        $userCredentials = array('TheUser', 'TheCredentials');

        $request = new Request(array(), array(), array(), array(), array(), array());

        $token = new PreAuthenticatedToken('AnotherUser', 'TheCredentials', 'TheProviderKey', array('ROLE_FOO'));

        $tokenStorage = $this->getMock('Makhan\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface');
        $tokenStorage
            ->expects($this->any())
            ->method('getToken')
            ->will($this->returnValue($token))
        ;
        $tokenStorage
            ->expects($this->once())
            ->method('setToken')
            ->with($this->equalTo(null))
        ;

        $exception = new AuthenticationException('Authentication failed.');
        $authenticationManager = $this->getMock('Makhan\Component\Security\Core\Authentication\AuthenticationManagerInterface');
        $authenticationManager
            ->expects($this->once())
            ->method('authenticate')
            ->with($this->isInstanceOf('Makhan\Component\Security\Core\Authentication\Token\PreAuthenticatedToken'))
            ->will($this->throwException($exception))
        ;

        $listener = $this->getMockForAbstractClass('Makhan\Component\Security\Http\Firewall\AbstractPreAuthenticatedListener', array(
            $tokenStorage,
            $authenticationManager,
            'TheProviderKey',
        ));
        $listener
            ->expects($this->once())
            ->method('getPreAuthenticatedData')
            ->will($this->returnValue($userCredentials));

        $event = $this->getMock('Makhan\Component\HttpKernel\Event\GetResponseEvent', array(), array(), '', false);
        $event
            ->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($request))
        ;

        $listener->handle($event);
    }
}
