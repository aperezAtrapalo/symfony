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
use Makhan\Component\HttpFoundation\Response;
use Makhan\Component\Security\Http\Firewall\BasicAuthenticationListener;
use Makhan\Component\Security\Core\Authentication\AuthenticationProviderManager;

class BasicAuthenticationListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testHandleWithValidUsernameAndPasswordServerParameters()
    {
        $request = new Request(array(), array(), array(), array(), array(), array(
            'PHP_AUTH_USER' => 'TheUsername',
            'PHP_AUTH_PW' => 'ThePassword',
        ));

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
            ->with($this->isInstanceOf('Makhan\Component\Security\Core\Authentication\Token\UsernamePasswordToken'))
            ->will($this->returnValue($token))
        ;

        $listener = new BasicAuthenticationListener(
            $tokenStorage,
            $authenticationManager,
            'TheProviderKey',
            $this->getMock('Makhan\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface')
        );

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
        $request = new Request(array(), array(), array(), array(), array(), array(
            'PHP_AUTH_USER' => 'TheUsername',
            'PHP_AUTH_PW' => 'ThePassword',
        ));

        $token = $this->getMock('Makhan\Component\Security\Core\Authentication\Token\TokenInterface');

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

        $response = new Response();

        $authenticationEntryPoint = $this->getMock('Makhan\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface');
        $authenticationEntryPoint
            ->expects($this->any())
            ->method('start')
            ->with($this->equalTo($request), $this->isInstanceOf('Makhan\Component\Security\Core\Exception\AuthenticationException'))
            ->will($this->returnValue($response))
        ;

        $listener = new BasicAuthenticationListener(
            $tokenStorage,
            new AuthenticationProviderManager(array($this->getMock('Makhan\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface'))),
            'TheProviderKey',
            $authenticationEntryPoint
        );

        $event = $this->getMock('Makhan\Component\HttpKernel\Event\GetResponseEvent', array(), array(), '', false);
        $event
            ->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($request))
        ;
        $event
            ->expects($this->once())
            ->method('setResponse')
            ->with($this->equalTo($response))
        ;

        $listener->handle($event);
    }

    public function testHandleWithNoUsernameServerParameter()
    {
        $request = new Request();

        $tokenStorage = $this->getMock('Makhan\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface');
        $tokenStorage
            ->expects($this->never())
            ->method('getToken')
        ;

        $listener = new BasicAuthenticationListener(
            $tokenStorage,
            $this->getMock('Makhan\Component\Security\Core\Authentication\AuthenticationManagerInterface'),
            'TheProviderKey',
            $this->getMock('Makhan\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface')
        );

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
        $request = new Request(array(), array(), array(), array(), array(), array('PHP_AUTH_USER' => 'TheUsername'));

        $token = new UsernamePasswordToken('TheUsername', 'ThePassword', 'TheProviderKey', array('ROLE_FOO'));

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

        $listener = new BasicAuthenticationListener(
            $tokenStorage,
            $authenticationManager,
            'TheProviderKey',
            $this->getMock('Makhan\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface')
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
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $providerKey must not be empty
     */
    public function testItRequiresProviderKey()
    {
        new BasicAuthenticationListener(
            $this->getMock('Makhan\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface'),
            $this->getMock('Makhan\Component\Security\Core\Authentication\AuthenticationManagerInterface'),
            '',
            $this->getMock('Makhan\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface')
        );
    }

    public function testHandleWithADifferentAuthenticatedToken()
    {
        $request = new Request(array(), array(), array(), array(), array(), array(
            'PHP_AUTH_USER' => 'TheUsername',
            'PHP_AUTH_PW' => 'ThePassword',
        ));

        $token = new PreAuthenticatedToken('TheUser', 'TheCredentials', 'TheProviderKey', array('ROLE_FOO'));

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

        $response = new Response();

        $authenticationEntryPoint = $this->getMock('Makhan\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface');
        $authenticationEntryPoint
            ->expects($this->any())
            ->method('start')
            ->with($this->equalTo($request), $this->isInstanceOf('Makhan\Component\Security\Core\Exception\AuthenticationException'))
            ->will($this->returnValue($response))
        ;

        $listener = new BasicAuthenticationListener(
            $tokenStorage,
            new AuthenticationProviderManager(array($this->getMock('Makhan\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface'))),
            'TheProviderKey',
            $authenticationEntryPoint
        );

        $event = $this->getMock('Makhan\Component\HttpKernel\Event\GetResponseEvent', array(), array(), '', false);
        $event
            ->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($request))
        ;
        $event
            ->expects($this->once())
            ->method('setResponse')
            ->with($this->equalTo($response))
        ;

        $listener->handle($event);
    }
}
