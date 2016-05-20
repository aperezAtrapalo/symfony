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
use Makhan\Component\HttpFoundation\Response;
use Makhan\Component\Security\Http\Firewall\LogoutListener;

class LogoutListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testHandleUnmatchedPath()
    {
        list($listener, $tokenStorage, $httpUtils, $options) = $this->getListener();

        list($event, $request) = $this->getGetResponseEvent();

        $event->expects($this->never())
            ->method('setResponse');

        $httpUtils->expects($this->once())
            ->method('checkRequestPath')
            ->with($request, $options['logout_path'])
            ->will($this->returnValue(false));

        $listener->handle($event);
    }

    public function testHandleMatchedPathWithSuccessHandlerAndCsrfValidation()
    {
        $successHandler = $this->getSuccessHandler();
        $tokenManager = $this->getTokenManager();

        list($listener, $tokenStorage, $httpUtils, $options) = $this->getListener($successHandler, $tokenManager);

        list($event, $request) = $this->getGetResponseEvent();

        $request->query->set('_csrf_token', 'token');

        $httpUtils->expects($this->once())
            ->method('checkRequestPath')
            ->with($request, $options['logout_path'])
            ->will($this->returnValue(true));

        $tokenManager->expects($this->once())
            ->method('isTokenValid')
            ->will($this->returnValue(true));

        $successHandler->expects($this->once())
            ->method('onLogoutSuccess')
            ->with($request)
            ->will($this->returnValue($response = new Response()));

        $tokenStorage->expects($this->once())
            ->method('getToken')
            ->will($this->returnValue($token = $this->getToken()));

        $handler = $this->getHandler();
        $handler->expects($this->once())
            ->method('logout')
            ->with($request, $response, $token);

        $tokenStorage->expects($this->once())
            ->method('setToken')
            ->with(null);

        $event->expects($this->once())
            ->method('setResponse')
            ->with($response);

        $listener->addHandler($handler);

        $listener->handle($event);
    }

    public function testHandleMatchedPathWithoutSuccessHandlerAndCsrfValidation()
    {
        $successHandler = $this->getSuccessHandler();

        list($listener, $tokenStorage, $httpUtils, $options) = $this->getListener($successHandler);

        list($event, $request) = $this->getGetResponseEvent();

        $httpUtils->expects($this->once())
            ->method('checkRequestPath')
            ->with($request, $options['logout_path'])
            ->will($this->returnValue(true));

        $successHandler->expects($this->once())
            ->method('onLogoutSuccess')
            ->with($request)
            ->will($this->returnValue($response = new Response()));

        $tokenStorage->expects($this->once())
            ->method('getToken')
            ->will($this->returnValue($token = $this->getToken()));

        $handler = $this->getHandler();
        $handler->expects($this->once())
            ->method('logout')
            ->with($request, $response, $token);

        $tokenStorage->expects($this->once())
            ->method('setToken')
            ->with(null);

        $event->expects($this->once())
            ->method('setResponse')
            ->with($response);

        $listener->addHandler($handler);

        $listener->handle($event);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testSuccessHandlerReturnsNonResponse()
    {
        $successHandler = $this->getSuccessHandler();

        list($listener, $tokenStorage, $httpUtils, $options) = $this->getListener($successHandler);

        list($event, $request) = $this->getGetResponseEvent();

        $httpUtils->expects($this->once())
            ->method('checkRequestPath')
            ->with($request, $options['logout_path'])
            ->will($this->returnValue(true));

        $successHandler->expects($this->once())
            ->method('onLogoutSuccess')
            ->with($request)
            ->will($this->returnValue(null));

        $listener->handle($event);
    }

    /**
     * @expectedException \Makhan\Component\Security\Core\Exception\LogoutException
     */
    public function testCsrfValidationFails()
    {
        $tokenManager = $this->getTokenManager();

        list($listener, $tokenStorage, $httpUtils, $options) = $this->getListener(null, $tokenManager);

        list($event, $request) = $this->getGetResponseEvent();

        $request->query->set('_csrf_token', 'token');

        $httpUtils->expects($this->once())
            ->method('checkRequestPath')
            ->with($request, $options['logout_path'])
            ->will($this->returnValue(true));

        $tokenManager->expects($this->once())
            ->method('isTokenValid')
            ->will($this->returnValue(false));

        $listener->handle($event);
    }

    private function getTokenManager()
    {
        return $this->getMock('Makhan\Component\Security\Csrf\CsrfTokenManagerInterface');
    }

    private function getTokenStorage()
    {
        return $this->getMock('Makhan\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface');
    }

    private function getGetResponseEvent()
    {
        $event = $this->getMockBuilder('Makhan\Component\HttpKernel\Event\GetResponseEvent')
            ->disableOriginalConstructor()
            ->getMock();

        $event->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($request = new Request()));

        return array($event, $request);
    }

    private function getHandler()
    {
        return $this->getMock('Makhan\Component\Security\Http\Logout\LogoutHandlerInterface');
    }

    private function getHttpUtils()
    {
        return $this->getMockBuilder('Makhan\Component\Security\Http\HttpUtils')
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function getListener($successHandler = null, $tokenManager = null)
    {
        $listener = new LogoutListener(
            $tokenStorage = $this->getTokenStorage(),
            $httpUtils = $this->getHttpUtils(),
            $successHandler ?: $this->getSuccessHandler(),
            $options = array(
                'csrf_parameter' => '_csrf_token',
                'csrf_token_id' => 'logout',
                'logout_path' => '/logout',
                'target_url' => '/',
            ),
            $tokenManager
        );

        return array($listener, $tokenStorage, $httpUtils, $options);
    }

    private function getSuccessHandler()
    {
        return $this->getMock('Makhan\Component\Security\Http\Logout\LogoutSuccessHandlerInterface');
    }

    private function getToken()
    {
        return $this->getMock('Makhan\Component\Security\Core\Authentication\Token\TokenInterface');
    }
}
