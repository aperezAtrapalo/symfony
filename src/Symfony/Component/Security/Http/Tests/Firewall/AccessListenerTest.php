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

use Makhan\Component\Security\Http\Firewall\AccessListener;

class AccessListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Makhan\Component\Security\Core\Exception\AccessDeniedException
     */
    public function testHandleWhenTheAccessDecisionManagerDecidesToRefuseAccess()
    {
        $request = $this->getMock('Makhan\Component\HttpFoundation\Request', array(), array(), '', false, false);

        $accessMap = $this->getMock('Makhan\Component\Security\Http\AccessMapInterface');
        $accessMap
            ->expects($this->any())
            ->method('getPatterns')
            ->with($this->equalTo($request))
            ->will($this->returnValue(array(array('foo' => 'bar'), null)))
        ;

        $token = $this->getMock('Makhan\Component\Security\Core\Authentication\Token\TokenInterface');
        $token
            ->expects($this->any())
            ->method('isAuthenticated')
            ->will($this->returnValue(true))
        ;

        $tokenStorage = $this->getMock('Makhan\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface');
        $tokenStorage
            ->expects($this->any())
            ->method('getToken')
            ->will($this->returnValue($token))
        ;

        $accessDecisionManager = $this->getMock('Makhan\Component\Security\Core\Authorization\AccessDecisionManagerInterface');
        $accessDecisionManager
            ->expects($this->once())
            ->method('decide')
            ->with($this->equalTo($token), $this->equalTo(array('foo' => 'bar')), $this->equalTo($request))
            ->will($this->returnValue(false))
        ;

        $listener = new AccessListener(
            $tokenStorage,
            $accessDecisionManager,
            $accessMap,
            $this->getMock('Makhan\Component\Security\Core\Authentication\AuthenticationManagerInterface')
        );

        $event = $this->getMock('Makhan\Component\HttpKernel\Event\GetResponseEvent', array(), array(), '', false);
        $event
            ->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($request))
        ;

        $listener->handle($event);
    }

    public function testHandleWhenTheTokenIsNotAuthenticated()
    {
        $request = $this->getMock('Makhan\Component\HttpFoundation\Request', array(), array(), '', false, false);

        $accessMap = $this->getMock('Makhan\Component\Security\Http\AccessMapInterface');
        $accessMap
            ->expects($this->any())
            ->method('getPatterns')
            ->with($this->equalTo($request))
            ->will($this->returnValue(array(array('foo' => 'bar'), null)))
        ;

        $notAuthenticatedToken = $this->getMock('Makhan\Component\Security\Core\Authentication\Token\TokenInterface');
        $notAuthenticatedToken
            ->expects($this->any())
            ->method('isAuthenticated')
            ->will($this->returnValue(false))
        ;

        $authenticatedToken = $this->getMock('Makhan\Component\Security\Core\Authentication\Token\TokenInterface');
        $authenticatedToken
            ->expects($this->any())
            ->method('isAuthenticated')
            ->will($this->returnValue(true))
        ;

        $authManager = $this->getMock('Makhan\Component\Security\Core\Authentication\AuthenticationManagerInterface');
        $authManager
            ->expects($this->once())
            ->method('authenticate')
            ->with($this->equalTo($notAuthenticatedToken))
            ->will($this->returnValue($authenticatedToken))
        ;

        $tokenStorage = $this->getMock('Makhan\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface');
        $tokenStorage
            ->expects($this->any())
            ->method('getToken')
            ->will($this->returnValue($notAuthenticatedToken))
        ;
        $tokenStorage
            ->expects($this->once())
            ->method('setToken')
            ->with($this->equalTo($authenticatedToken))
        ;

        $accessDecisionManager = $this->getMock('Makhan\Component\Security\Core\Authorization\AccessDecisionManagerInterface');
        $accessDecisionManager
            ->expects($this->once())
            ->method('decide')
            ->with($this->equalTo($authenticatedToken), $this->equalTo(array('foo' => 'bar')), $this->equalTo($request))
            ->will($this->returnValue(true))
        ;

        $listener = new AccessListener(
            $tokenStorage,
            $accessDecisionManager,
            $accessMap,
            $authManager
        );

        $event = $this->getMock('Makhan\Component\HttpKernel\Event\GetResponseEvent', array(), array(), '', false);
        $event
            ->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($request))
        ;

        $listener->handle($event);
    }

    public function testHandleWhenThereIsNoAccessMapEntryMatchingTheRequest()
    {
        $request = $this->getMock('Makhan\Component\HttpFoundation\Request', array(), array(), '', false, false);

        $accessMap = $this->getMock('Makhan\Component\Security\Http\AccessMapInterface');
        $accessMap
            ->expects($this->any())
            ->method('getPatterns')
            ->with($this->equalTo($request))
            ->will($this->returnValue(array(null, null)))
        ;

        $token = $this->getMock('Makhan\Component\Security\Core\Authentication\Token\TokenInterface');
        $token
            ->expects($this->never())
            ->method('isAuthenticated')
        ;

        $tokenStorage = $this->getMock('Makhan\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface');
        $tokenStorage
            ->expects($this->any())
            ->method('getToken')
            ->will($this->returnValue($token))
        ;

        $listener = new AccessListener(
            $tokenStorage,
            $this->getMock('Makhan\Component\Security\Core\Authorization\AccessDecisionManagerInterface'),
            $accessMap,
            $this->getMock('Makhan\Component\Security\Core\Authentication\AuthenticationManagerInterface')
        );

        $event = $this->getMock('Makhan\Component\HttpKernel\Event\GetResponseEvent', array(), array(), '', false);
        $event
            ->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($request))
        ;

        $listener->handle($event);
    }

    /**
     * @expectedException \Makhan\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException
     */
    public function testHandleWhenTheSecurityTokenStorageHasNoToken()
    {
        $tokenStorage = $this->getMock('Makhan\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface');
        $tokenStorage
            ->expects($this->any())
            ->method('getToken')
            ->will($this->returnValue(null))
        ;

        $listener = new AccessListener(
            $tokenStorage,
            $this->getMock('Makhan\Component\Security\Core\Authorization\AccessDecisionManagerInterface'),
            $this->getMock('Makhan\Component\Security\Http\AccessMapInterface'),
            $this->getMock('Makhan\Component\Security\Core\Authentication\AuthenticationManagerInterface')
        );

        $event = $this->getMock('Makhan\Component\HttpKernel\Event\GetResponseEvent', array(), array(), '', false);

        $listener->handle($event);
    }
}
